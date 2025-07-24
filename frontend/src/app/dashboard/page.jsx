import React, { useState } from 'react';
import CardBox from '@/components/card.jsx';
import { Container } from '@radix-ui/themes';
import IoTDashboard from '@/components/iot-dashboard.jsx';
function DashboardPage() {
    return (
      <>
    {/* // <div className="relative min-h-screen bg-black overflow-hidden text-white"> */}
      {/* 🌊 Wave background SVG */}
      {/* <svg
        className="absolute top-0 left-0 w-full h-full opacity-10 z-0 pointer-events-none"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 320"
        preserveAspectRatio="none"
      >
        <path
          fill="#0090FF"
          fillOpacity="1"
          d="M0,64L30,74.7C60,85,120,107,180,117.3C240,128,300,128,360,122.7C420,117,480,107,540,122.7C600,139,660,181,720,197.3C780,213,840,203,900,181.3C960,160,1020,128,1080,133.3C1140,139,1200,181,1260,176C1320,171,1380,117,1410,90.7L1440,64L1440,0L0,0Z"
        />
      </svg> */}

      {/* 🌌 Diffuse glow overlay */}
      {/* <div className="absolute inset-0 z-0 bg-gradient-radial from-[#0090FF]/30 via-transparent to-black opacity-70 pointer-events-none"></div> */}

      {/* 🧠 Main content */}
      <main>

      <IoTDashboard />
      </main>
      {/* </div> */}
      </>
      
    );
}
export default DashboardPage;

