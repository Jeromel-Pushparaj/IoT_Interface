# Frontend Code Enhancement Recommendations

Here are recommendations to improve the code standards, reusability, and performance of the frontend application.

## 1. State Management and Data Fetching (High Impact)

### Problem: Redundant API Calls and Manual State Management
Currently, multiple components (`DeviceTable.jsx`, `control/page.jsx`, `iot-dashboard.jsx`) independently fetch the same device list (`/api/devices`) using `useEffect`. This leads to:
- **Redundant Network Requests:** The same data is fetched multiple times, increasing network traffic and backend load.
- **Boilerplate Code:** Each component repeats the logic for fetching, loading, and error states.
- **Lack of Caching:** Data is not cached, so navigating between pages triggers new fetches for the same data.

### Recommendation: Use a Data Fetching Library (React Query)
Integrate a library like **TanStack React Query** (`@tanstack/react-query`).

**Benefits:**
- **Automatic Caching:** Fetches data once and caches it. Subsequent requests for the same data are served from the cache instantly.
- **Reduced Boilerplate:** Eliminates the need for manual `useState` and `useEffect` for data fetching.
- **Background Refetching:** Keeps data fresh automatically.
- **Improved User Experience:** UI feels faster as data is available instantly from the cache.

**Example Implementation:**

1.  **Install:** `npm install @tanstack/react-query`
2.  **Setup Provider in `main.jsx`:**
    ```jsx
    // ...
    import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

    const queryClient = new QueryClient();

    createRoot(document.getElementById("root")).render(
      <StrictMode>
        <BrowserRouter>
          <QueryClientProvider client={queryClient}>
            {/* ... your Theme and other providers */}
          </QueryClientProvider>
        </BrowserRouter>
      </StrictMode>
    );
    ```
3.  **Create a custom hook `useDevices.js`:**
    ```javascript
    import { useQuery } from '@tanstack/react-query';
    import api from '@/api';

    const fetchDevices = async () => {
      const { data } = await api.get('/api/devices');
      return data;
    };

    export const useDevices = () => {
      return useQuery({
        queryKey: ['devices'], // Unique key for this query
        queryFn: fetchDevices,
      });
    };
    ```
4.  **Use the hook in components:**
    ```jsx
    // In DeviceTable.jsx, control/page.jsx, etc.
    import { useDevices } from '@/hooks/useDevices'; // Adjust path

    function DeviceTable() {
      const { data: devices, isLoading, isError } = useDevices();

      if (isLoading) return <div>Loading devices...</div>;
      if (isError) return <div>Error fetching devices.</div>;

      // ... render the table with the 'devices' data
    }
    ```

## 2. Critical Bug: Unwanted API Calls on Render (High Impact)

### Problem
In `src/components/BleQrConnect.jsx`, an API call is made in the component's body, causing it to execute on every render. The `|| true` condition makes this even more dangerous.

```javascript
// This runs on EVERY render where qrData is not null
if ((isConnected || true) && qrData != null) {
  api.post(`/api/devices`, qrData.deviceData)
    // ...
}
```

### Recommendation: Move Logic to Event Handler or `useEffect`
This API call should only be made *once* when the device is successfully connected. Move this logic into a `useEffect` hook that triggers when the connection status changes, or into the `handleConnectBLE` function itself.

**Example Fix:**

```javascript
// In BleQrConnect.jsx

const handleConnectBLE = async () => {
  try {
    // ... BLE connection logic ...
    const server = await device.gatt.connect();
    // ...
    
    // If connection is successful, then register the device
    if (server.connected && qrData?.deviceData) {
        api.post(`/api/devices`, qrData.deviceData)
          .then((response) => {
            console.log('Device registered:', response.data);
            alert(`Connected to ${qrData.name} and registered.`);
          })
          .catch((error) => {
            console.error('Error in registering device:', error);
          });
    }

    setIsConnected(true);
  } catch (error) {
    console.error('BLE connection error:', error);
    alert('BLE connection failed');
  }
};
```

## 3. Architectural Improvements

### Problem: Misplaced Responsibility in Real-time Updates
In `src/components/DeviceStatus.jsx`, the frontend listens for an MQTT message and then makes a `PUT` request to the backend to update the device status in the database.

```javascript
// In DeviceStatus.jsx's useMqttSubscription callback
api.put(`/api/devices/status`, {
  device_id: String(deviceId),
  status: normalized
})
```

This creates a circular and inefficient flow: `Device -> MQTT Broker -> Frontend -> Backend API -> Database`.

### Recommendation: Backend-Driven Status Updates
The backend should be solely responsible for updating the database based on MQTT messages. The frontend should only listen and display the status.

**Proposed Flow:**
1.  **Device:** Publishes its status (e.g., 'online') to `device/{deviceId}/status`.
2.  **Backend MQTT Subscriber (`mqtt-subscriber.py`):** Subscribes to `device/+/status`. When a message arrives, it updates the corresponding device's status in the MongoDB database.
3.  **Frontend:**
    *   Gets the initial status of devices via a REST API call (as it does now, preferably with React Query).
    *   Subscribes to the same MQTT topic (`device/{deviceId}/status`) and updates its *local component state* to reflect real-time changes without calling the API.

This simplifies the frontend's role to presentation only and makes the backend the single source of truth for database writes.

## 4. Code Standardization and Best Practices

### Problem: Inconsistencies in Code Style
The codebase has a mix of file naming conventions (e.g., `page.jsx` vs. `AddDevice.jsx`), and a mix of JavaScript and TypeScript (`.js`, `.jsx`, `.ts`).

### Recommendations:
1.  **Adopt TypeScript:** Gradually migrate components from `.jsx` to `.tsx`. This will provide strong type safety, reduce runtime errors, and improve code completion. Start with new components and gradually convert existing ones. The project already has a `ts` file and `@types` dependencies, so the setup is halfway there.
2.  **Standardize File Naming:** Use **PascalCase** for all React component files (e.g., `page.jsx` -> `LoginPage.jsx`, `control/page.jsx` -> `ControlPage.jsx`). This is a standard convention in the React ecosystem.
3.  **Improve Form Handling:** For forms like in `LoginCard.jsx` and `SignupCard.jsx`, replace `useRef` with a dedicated form library like **React Hook Form** combined with **Zod** (which is already a dependency) for schema validation. This provides a more robust and scalable way to manage form state, validation, and submission.
4.  **Prop-Types to TypeScript:** As you migrate to TypeScript, replace `PropTypes` with TypeScript interfaces or types for prop definitions.

## 5. Enhancing Reusability

### Problem: Duplicated Data-Fetching Logic
As mentioned in point #1, device fetching logic is duplicated across multiple components.

### Recommendation: Create Custom Hooks
In addition to `useDevices` (recommended above), create other custom hooks for any repeated logic.

- **`useAuth.js`:** A hook to provide authentication status and user information from the JWT token.
  ```javascript
  import { useState, useEffect } from 'react';
  import { isTokenExpired } from '@utils/auth';
  import { jwtDecode } from 'jwt-decode';

  export const useAuth = (navigate) => {
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [user, setUser] = useState(null);
    const [isAuthenticated, setIsAuthenticated] = useState(false);

    useEffect(() => {
      if (token) {
        if (isTokenExpired(token)) {
          localStorage.removeItem('token');
          setToken(null);
          setUser(null);
          setIsAuthenticated(false);
          if (window.location.pathname !== '/login' && window.location.pathname !== '/signup') {
            navigate('/login');
          }
        } else {
          setUser(jwtDecode(token));
          setIsAuthenticated(true);
        }
      } else {
        setUser(null);
        setIsAuthenticated(false);
      }
    }, [token, navigate]);

    return { user, isAuthenticated };
  };
  ```

This provides a clean way to access auth state throughout the application.
