import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import "./index.css";
import App from "@/App.jsx";
import { Theme, Box, Flex } from "@radix-ui/themes";
import Background from "./components/Background.jsx";
import Header from "@/components/Header.jsx";
import {
  QueryClient,
  QueryClientProvider,
} from "@tanstack/react-query";

const queryClient = new QueryClient();

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <BrowserRouter>
      <QueryClientProvider client={queryClient}>
        <Theme
          panelBackground="translucent"
          accentColor="indigo"
          grayColor="sand"
          radius="large"
          scaling="95%"
          appearance="dark"
        >
          <Background />

          <Box className="bg-transparent absolute left-0 right-0 min-h-screen " style={{ backgroundColor: 'transparent' }}>
            <Flex>
              <Box style={{ flex: 1 }}>
                <Box
                  style={{
                    backgroundColor: "transparent",
                    padding: "1rem 2rem",
                  }}
                >
                  <Header />
                </Box>
                <App />
              </Box>
            </Flex>
          </Box>
        </Theme>
      </QueryClientProvider>
    </BrowserRouter>
  </StrictMode>
);
