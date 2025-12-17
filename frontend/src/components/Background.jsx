import React, { useEffect, useMemo, useState } from "react";
import Particles, { initParticlesEngine } from "@tsparticles/react";
import { loadSlim } from "@tsparticles/slim";

const Background = () => {
  const [init, setInit] = useState(false);

  // this should be run only once per application lifetime
  useEffect(() => {
    initParticlesEngine(async (engine) => {
      await loadSlim(engine);
    }).then(() => {
      setInit(true);
    });
  }, []);

  const particlesLoaded = (container) => {
    console.log("Particles container loaded", container);
  };

  const options = useMemo(
    () => ({
      background: {
        color: {
          value: 'transparent',
        },
      },
      fullScreen: {
        enable: true,
        zIndex: 0, // Set to 0 to be on the base layer
      },
      particles: {
        number: {
          value: 80,
        },
        color: {
          value: "#ffffff",
        },
        shape: {
          type: "circle",
        },
        opacity: {
          value: 0.5,
          random: true,
        },
        size: {
          value: { min: 1, max: 3 },
        },
        move: {
          enable: true,
          speed: 1, // Slowed down for subtlety
          direction: "none",
          random: true,
          straight: false,
          outModes: "out",
        },
      },
      interactivity: {
        events: {
          onHover: {
            enable: false,
          },
          onClick: {
            enable: false,
          },
        },
      },
    }),
    [],
  );

  if (init) {
    return (
      <>
        {/* This div provides the overall page background color/gradient */}
        {/* It's fixed and z-[-1] so it's behind the particles */}
        <div className="fixed top-0 left-0 w-full h-full z-[-1] bg-[linear-gradient(to_bottom,_var(--accent-4),_#0f172a)] opacity-60"></div>

        <Particles
          id="tsparticles"
          particlesLoaded={particlesLoaded}
          options={options}
        />
      </>
    );
  }

  return <></>;
};

export default Background;
