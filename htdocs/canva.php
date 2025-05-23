<style>
    body {
  overflow: hidden;
  user-select: none;

  /* stuff that sass won't compile to */
  -webkit-touch-callout: none;
  -khtml-user-select: none;
}

canvas {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 0;
}

</style>

<canvas id="canvas" width="200" height="100" style="border:1px solid #000000;"></canvas>
 
<script>
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const pi = Math.PI;

// background gradient
var gradient;
function fixDpiResizeCanvas() {
  const dpr = window.devicePixelRatio || 1;
  canvas.style.width = window.innerWidth + "px";
  canvas.style.height = window.innerHeight + "px";
  canvas.width = window.innerWidth * dpr;
  canvas.height = window.innerHeight * dpr;
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

  // sorry for not using css
  gradient = ctx.createLinearGradient(0, canvas.height, canvas.width, 0);
  gradient.addColorStop(0, "#88d8ff");
  gradient.addColorStop(1, "#d899ff");
}
fixDpiResizeCanvas();
window.addEventListener("resize", fixDpiResizeCanvas);

//

/////////////////////////
//  JOYSTICK UPDATING  //
/////////////////////////

var positions = {
  // Here, fixed is the outer circle and inner is the small circle that moves
  fixedX: undefined,
  fixedY: undefined,
  innerX: undefined,
  innerY: undefined
};

var angle = undefined;

function touchStart(x, y) {
  if (positions.fixedX || positions.fixedY) return;
  positions.fixedX = positions.innerX = x;
  positions.fixedY = positions.innerY = y;
}

function touchMove(x, y) {
  if (!(positions.fixedX || positions.fixedY)) return;
  
  positions.innerX = x;
  positions.innerY = y;

  angle = Math.atan2(
    positions.innerY - positions.fixedY,
    positions.innerX - positions.fixedX
  );

  // If inner circle is outside joystick radius, reduce it to the circumference
  if (!(
    (x - positions.fixedX) ** 2 +
    (y - positions.fixedY) ** 2 < 10000
    ))
  {
    positions.innerX = Math.round(Math.cos(angle) * 100 + positions.fixedX);
    positions.innerY = Math.round(Math.sin(angle) * 100 + positions.fixedY);
  }
}

function touchEndOrCancel() {
  positions.fixedX
  = positions.fixedY
  = positions.innerX
  = positions.innerY
  = angle
  = undefined;
}

canvas.addEventListener("touchstart", function(e) {
  touchStart(e.touches[0].clientX, e.touches[0].clientY);
});

canvas.addEventListener("touchmove", function(e) {
  touchMove(e.touches[0].clientX, e.touches[0].clientY)
});

canvas.addEventListener("touchend", touchEndOrCancel);
canvas.addEventListener("touchcancel", touchEndOrCancel);

// TODO: test mouse on pc
canvas.addEventListener("mousedown", function (e) {
  touchStart(e.offsetX, e.offsetY);
});

canvas.addEventListener("mousemove", function (e) {
  touchMove(e.offsetX, e.offsetY);
});

canvas.addEventListener("mouseup", touchEndOrCancel);

function renderLoop() {
  requestAnimationFrame(renderLoop);

  
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Background gradient
  ctx.fillStyle = gradient;
  ctx.fillRect(0, 0, canvas.width, canvas.height);


  // Invert Y axis and turn into positive
  var displayAngle = (-angle + 2*pi) % (2*pi);
  
  
  
  ctx.fillStyle = "#0008";
  // Draw other message if not touching screen
  if (!(positions.fixedX || positions.fixedY)) {
    ctx.fillText("Touch the screen", 20, 20);
    return;
  };
  
  // Display data
  ctx.fillText(
    `Angle: ${Math.round((displayAngle * 180) / pi)} degrees (${
      Math.round(displayAngle * 100) / 100
    } radians)`,
    20,
    20
  );

  ctx.fillText(`Raw angle: ${Math.round(angle * 100) / 100} radians (inverted Y axis)`, 20, 50);
  ctx.fillText(`Inner joystick: (${positions.innerX},${positions.innerY})`, 20, 80);
  ctx.fillText(`Touch start point: (${positions.fixedX},${positions.fixedY}) or (${Math.round(positions.fixedX/window.innerWidth*1000)/1000},${Math.round(positions.fixedY/window.innerHeight*1000)/1000})`, 20, 110);
  
  // Draw joystick outer circle
  ctx.beginPath();
  ctx.fillStyle = "#0004";
  ctx.arc(positions.fixedX, positions.fixedY, 100, 0, 2 * pi);
  ctx.fill();
  ctx.closePath();

  // Draw inner circle
  ctx.beginPath();
  ctx.fillStyle = "#0008";
  ctx.arc(positions.innerX, positions.innerY, 30, 0, 2 * pi);
  ctx.fill();
  ctx.closePath();
}

renderLoop();

ctx.font = "20px Helvetica";
ctx.textBaseline = "top";







/*var keys = {37: 1, 38: 1, 39: 1, 40: 1};

function preventDefault(e) {
  e.preventDefault();
}

function preventDefaultForScrollKeys(e) {
  if (keys[e.keyCode]) {
    preventDefault(e);
    return false;
  }
}

// modern Chrome requires { passive: false } when adding event
var supportsPassive = false;
try {
  window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
    get: function () { supportsPassive = true; } 
  }));
} catch(e) {}

var wheelOpt = supportsPassive ? { passive: false } : false;
var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
window.addEventListener('keydown', preventDefaultForScrollKeys, false);*/
</script>