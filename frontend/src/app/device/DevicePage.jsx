import DeviceLayout from "@/components/DeviceLayout";
import React from "react";
import DeviceTable from "@/components/DeviceTable";
function DevicePage() {

  return (
    <DeviceLayout page={"Device Management"}>
      <DeviceTable /> 
    </DeviceLayout> 
  );
}

export default DevicePage;