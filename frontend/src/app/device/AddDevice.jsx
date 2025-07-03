import React, { useState } from 'react';
import DeviceLayout from '@/components/device-layout';
import BleQrConnect from '@/components/BleQrConnect';
function AddDevice() {
    return (
    <DeviceLayout page="Add Device">
        <BleQrConnect />
        {/* Add more components or features related to adding a device here */}
        {/* Placeholder for add device content */}
    </DeviceLayout>
    );
}

export default AddDevice;