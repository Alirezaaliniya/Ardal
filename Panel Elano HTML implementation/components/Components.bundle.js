
function __figIcon(props, size, color, sw, paths, extra) {
  const h = React.createElement;
  return h("div", { className: props.className, style: { width: size, height: size, overflow: "hidden", position: "relative", color, ...props.style } },
    h("svg", { width: "100%", height: "100%", viewBox: "0 0 " + size + " " + size, fill: "none", stroke: "currentColor", strokeWidth: sw, strokeLinecap: "round", strokeLinejoin: "round", style: { position: "absolute", inset: 0, display: "block" } },
      ...paths.map((d, i) => h("path", { key: i, d })), ...(extra || []).map((e, i) => h(e[0], { key: "e" + i, ...e[1] }))));
}
// Components bundle — 25 component(s) materialized from a .fig as one
// self-contained file: no imports/exports; every component is assigned to window below.
// Design tokens / typography still ship separately (fig-tokens.css / fig-typography.css).

// figma node: 1:165 COCO/Line/Menu
function COCOLineMenu(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 40,
      height: 40,
      overflow: "hidden",
      position: "relative",
      color: "rgb(241,23,99)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 11.667,
    viewBox: "0 0 11.667 11.667",
    fill: "none",
    style: {
      position: "absolute",
      left: 5,
      top: 5,
      width: 11.667,
      height: 11.667,
      borderRadius: 10
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 1.108 1.108 L 5.833 0 L 10.558 1.108 L 11.667 5.833 L 10.558 10.558 L 5.833 11.667 L 1.108 10.558 L 0 5.833 L 1.108 1.108 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 11.667,
    viewBox: "0 0 11.667 11.667",
    fill: "none",
    style: {
      position: "absolute",
      left: 5,
      top: 23.333,
      width: 11.667,
      height: 11.667,
      borderRadius: 10
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 1.108 1.108 L 5.833 0 L 10.558 1.108 L 11.667 5.833 L 10.558 10.558 L 5.833 11.667 L 1.108 10.558 L 0 5.833 L 1.108 1.108 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 11.667,
    viewBox: "0 0 11.667 11.667",
    fill: "none",
    style: {
      position: "absolute",
      left: 23.333,
      top: 5,
      width: 11.667,
      height: 11.667,
      borderRadius: 10
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 1.108 1.108 L 5.833 0 L 10.558 1.108 L 11.667 5.833 L 10.558 10.558 L 5.833 11.667 L 1.108 10.558 L 0 5.833 L 1.108 1.108 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 11.667,
    viewBox: "0 0 11.667 11.667",
    fill: "none",
    style: {
      position: "absolute",
      left: 23.333,
      top: 23.333,
      width: 11.667,
      height: 11.667,
      borderRadius: 10
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 1.108 1.108 L 5.833 0 L 10.558 1.108 L 11.667 5.833 L 10.558 10.558 L 5.833 11.667 L 1.108 10.558 L 0 5.833 L 1.108 1.108 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })));
}

// figma node: 1:277 COCO/Line/Saved
function COCOLineSaved(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 20,
      height: 20,
      overflow: "hidden",
      position: "relative",
      color: "rgb(255,255,255)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 15,
    viewBox: "0 0 11.667 15",
    fill: "none",
    style: {
      position: "absolute",
      left: 4.166,
      top: 2.5,
      width: 11.667,
      height: 15
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 9.472 14.8 L 9.096 15.449 L 9.096 15.449 L 9.472 14.8 Z M 6.816 13.263 L 7.191 12.614 L 7.191 12.614 L 6.816 13.263 Z M 4.851 13.263 L 5.227 13.912 L 5.227 13.912 L 4.851 13.263 Z M 2.195 14.8 L 1.819 14.151 L 1.819 14.151 L 2.195 14.8 Z M 3.134 0.263 L 2.989 -0.473 L 3.134 0.263 Z M 8.533 0.263 L 8.388 0.999 L 8.388 0.999 L 8.533 0.263 Z M 11.667 4.037 L 10.917 4.037 L 10.917 13.556 L 11.667 13.556 L 12.417 13.556 L 12.417 4.037 L 11.667 4.037 Z M 0 13.556 L 0.75 13.556 L 0.75 4.037 L 0 4.037 L -0.75 4.037 L -0.75 13.556 L 0 13.556 Z M 9.472 14.8 L 9.847 14.151 L 7.191 12.614 L 6.816 13.263 L 6.44 13.912 L 9.096 15.449 L 9.472 14.8 Z M 4.851 13.263 L 4.475 12.614 L 1.819 14.151 L 2.195 14.8 L 2.571 15.449 L 5.227 13.912 L 4.851 13.263 Z M 3.134 0.263 L 3.279 0.999 C 4.965 0.667 6.701 0.667 8.388 0.999 L 8.533 0.263 L 8.677 -0.473 C 6.8 -0.842 4.867 -0.842 2.989 -0.473 L 3.134 0.263 Z M 6.816 13.263 L 7.191 12.614 C 6.352 12.128 5.314 12.128 4.475 12.614 L 4.851 13.263 L 5.227 13.912 C 5.601 13.696 6.066 13.696 6.44 13.912 L 6.816 13.263 Z M 0 13.556 L -0.75 13.556 C -0.75 15.262 1.112 16.294 2.571 15.449 L 2.195 14.8 L 1.819 14.151 C 1.334 14.432 0.75 14.077 0.75 13.556 L 0 13.556 Z M 11.667 13.556 L 10.917 13.556 C 10.917 14.077 10.332 14.432 9.847 14.151 L 9.472 14.8 L 9.096 15.449 C 10.555 16.294 12.417 15.262 12.417 13.556 L 11.667 13.556 Z M 11.667 4.037 L 12.417 4.037 C 12.417 1.837 10.845 -0.047 8.677 -0.473 L 8.533 0.263 L 8.388 0.999 C 9.864 1.289 10.917 2.566 10.917 4.037 L 11.667 4.037 Z M 0 4.037 L 0.75 4.037 C 0.75 2.566 1.803 1.289 3.279 0.999 L 3.134 0.263 L 2.989 -0.473 C 0.822 -0.047 -0.75 1.837 -0.75 4.037 L 0 4.037 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })));
}

// figma node: 1:137 Linear / Shopping, Ecommerce / Cart Check
function LinearShoppingEcommerceCartCheck(_p = {}) {
  return __figIcon(_p, 40, "rgb(241,23,99)", 2, ["M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8", "M18.333 15.8l2.2 2.2 4.467-4.467"], [["circle",{"cx":12.5,"cy":32.5,"r":2.2}],["circle",{"cx":27.5,"cy":32.5,"r":2.2}]]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 40,
      height: 40,
      overflow: "hidden",
      position: "relative",
      color: "rgb(241,23,99)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,10,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,25,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 3.333,
      top: 5,
      width: 31.667,
      height: 20.833,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 18.333,
      top: 15,
      width: 6.667,
      height: 5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"));
}

// figma node: 1:131 Linear / Shopping, Ecommerce / Cart Cross
function LinearShoppingEcommerceCartCross(_p = {}) {
  return __figIcon(_p, 40, "rgb(241,23,99)", 2, ["M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8", "M19.167 13.333l5 5m0-5l-5 5"], [["circle",{"cx":12.5,"cy":32.5,"r":2.2}],["circle",{"cx":27.5,"cy":32.5,"r":2.2}]]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 40,
      height: 40,
      overflow: "hidden",
      position: "relative",
      color: "rgb(241,23,99)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,10,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,25,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 3.333,
      top: 5,
      width: 31.667,
      height: 20.833,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 19.167,
      top: 15.833,
      width: 5,
      height: 5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"));
}

// figma node: 1:144 Linear / Shopping, Ecommerce / Cart Large Minimalistic
function LinearShoppingEcommerceCartLarge(_p = {}) {
  return __figIcon(_p, 40, "rgb(241,23,99)", 2, ["M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8"], [["circle",{"cx":12.5,"cy":32.5,"r":2.2}],["circle",{"cx":27.5,"cy":32.5,"r":2.2}]]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 40,
      height: 40,
      overflow: "hidden",
      position: "relative",
      color: "rgb(241,23,99)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,10,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 5,
    height: 5,
    viewBox: "0 0 5 5",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,25,35)",
      transformOrigin: "0 0",
      width: 5,
      height: 5
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 5 2.5 C 5 3.881 3.881 5 2.5 5 C 1.119 5 0 3.881 0 2.5 C 0 1.119 1.119 0 2.5 0 C 3.881 0 5 1.119 5 2.5 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 3.333,
      top: 5,
      width: 31.667,
      height: 20.833,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"));
}

// figma node: 1:160 Outline / Essentional, UI / Add Circle
function OutlineEssentionalUIAddCircle(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 16,
      height: 16,
      overflow: "hidden",
      position: "relative",
      color: "rgb(47,47,51)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 14.333,
    height: 14.333,
    viewBox: "0 0 14.333 14.333",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.833,
      top: 0.833,
      width: 14.333,
      height: 14.333
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 7.167 4.667 C 7.443 4.667 7.667 4.891 7.667 5.167 M 6.667 5.167 C 6.667 4.891 6.891 4.667 7.167 4.667 M 6.667 6.667 L 6.667 5.167 M 5.167 6.667 L 6.667 6.667 M 4.667 7.167 C 4.667 6.891 4.891 6.667 5.167 6.667 M 5.167 7.667 C 4.891 7.667 4.667 7.443 4.667 7.167 M 6.667 7.667 L 5.167 7.667 M 6.667 9.167 L 6.667 7.667 M 7.167 9.667 C 6.891 9.667 6.667 9.443 6.667 9.167 M 7.667 9.167 C 7.667 9.443 7.443 9.667 7.167 9.667 M 7.667 7.667 L 7.667 9.167 M 9.167 7.667 L 7.667 7.667 M 9.667 7.167 C 9.667 7.443 9.443 7.667 9.167 7.667 M 9.167 6.667 C 9.443 6.667 9.667 6.891 9.667 7.167 M 7.667 6.667 L 9.167 6.667 M 7.667 5.167 L 7.667 6.667 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 0 7.167 C 0 3.209 3.209 0 7.167 0 M 7.167 14.333 C 3.209 14.333 0 11.125 0 7.167 M 14.333 7.167 C 14.333 11.125 11.125 14.333 7.167 14.333 M 7.167 0 C 11.125 0 14.333 3.209 14.333 7.167 Z M 7.167 1 C 3.761 1 1 3.761 1 7.167 M 13.333 7.167 C 13.333 3.761 10.572 1 7.167 1 M 7.167 13.333 C 10.572 13.333 13.333 10.572 13.333 7.167 M 1 7.167 C 1 10.572 3.761 13.333 7.167 13.333 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })));
}

// figma node: 1:170 vuesax/linear/arrow-left
function VuesaxLinearArrowLeft(_p = {}) {
  return __figIcon(_p, 24, "rgb(41,45,50)", 1.5, ["M15 19.92L8.48 13.4c-.77-.77-.77-2.03 0-2.8L15 4.08"]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 24,
      height: 24,
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 24,
      height: 24,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 24,
      height: 24,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 7.098,
    height: 15.840,
    viewBox: "0 0 7.098 15.840",
    fill: "none",
    style: {
      position: "absolute",
      left: 7.902,
      top: 4.08,
      width: 7.098,
      height: 15.84,
      color: "rgb(41,45,50)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 7.098 15.84 L 0.577 9.32 C -0.192 8.55 -0.192 7.29 0.577 6.52 L 7.098 0 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 24,
      height: 24,
      opacity: 0.3,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10
    }
  }, "Vector"))));
}

// figma node: 1:226 Dashboard
function Dashboard(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1216,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 946,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0644\u063A\u0648 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0645\u0631\u062C\u0648\u0639 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCheck, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062A\u062D\u0648\u06CC\u0644 \u0634\u062F\u0647")), /*#__PURE__*/React.createElement("svg", {
    width: 12.767,
    height: 12.763,
    viewBox: "0 0 12.767 12.763",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(-0.970,-0.244,-0.244,0.970,180,103.121)",
      transformOrigin: "0 0",
      width: 12.767,
      height: 12.763,
      filter: "drop-shadow(0px 4px 4px rgba(0,0,0,0.25))",
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 3.078 2.296 C -1.026 3.668 -1.026 5.906 3.078 7.272 L 4.889 7.873 L 5.491 9.685 C 6.856 13.788 9.101 13.788 10.466 9.685 L 12.501 3.587 C 13.407 0.849 11.92 -0.645 9.182 0.268 Z",
    fill: "rgb(255,255,255)",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 9.497 1.217 L 9.498 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.762 1.347 L 2.76 1.348 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.22 L 2.762 8.221 L 3.078 7.272 Z M 4.889 7.873 L 5.838 7.558 L 5.68 7.082 L 5.204 6.924 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10 L 4.542 10.001 L 5.491 9.685 Z M 10.466 9.685 L 9.518 9.368 L 9.518 9.369 L 10.466 9.685 Z M 12.501 3.587 L 13.45 3.904 L 13.451 3.901 L 12.501 3.587 Z M 9.182 0.268 L 8.867 -0.681 L 2.762 1.347 L 3.078 2.296 L 3.393 3.245 L 9.497 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.76 1.348 C 1.671 1.712 0.775 2.158 0.132 2.69 C -0.507 3.218 -1 3.925 -1 4.786 C -1 5.648 -0.507 6.354 0.133 6.882 C 0.776 7.413 1.672 7.858 2.762 8.22 L 3.078 7.272 L 3.393 6.323 C 2.432 6.003 1.789 5.656 1.406 5.34 C 1.02 5.021 1 4.827 1 4.786 C 1 4.746 1.02 4.551 1.407 4.231 C 1.79 3.914 2.433 3.566 3.395 3.245 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.221 L 4.574 8.822 L 4.889 7.873 L 5.204 6.924 L 3.393 6.323 L 3.078 7.272 Z M 4.889 7.873 L 3.94 8.188 L 4.542 10 L 5.491 9.685 L 6.44 9.37 L 5.838 7.558 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10.001 C 4.905 11.091 5.35 11.987 5.881 12.63 C 6.41 13.27 7.117 13.763 7.979 13.763 C 8.84 13.763 9.547 13.27 10.076 12.63 C 10.607 11.987 11.052 11.091 11.415 10.001 L 10.466 9.685 L 9.518 9.369 C 9.198 10.331 8.85 10.974 8.534 11.356 C 8.215 11.742 8.02 11.763 7.979 11.763 C 7.937 11.763 7.742 11.742 7.423 11.356 C 7.107 10.974 6.76 10.331 6.44 9.369 L 5.491 9.685 Z M 10.466 9.685 L 11.415 10.002 L 13.45 3.904 L 12.501 3.587 L 11.553 3.271 L 9.518 9.368 L 10.466 9.685 Z M 12.501 3.587 L 13.451 3.901 C 13.954 2.379 13.908 0.846 12.916 -0.148 C 11.922 -1.143 10.389 -1.188 8.866 -0.681 L 9.182 0.268 L 9.498 1.217 C 10.713 0.812 11.292 1.057 11.5 1.265 C 11.71 1.475 11.954 2.057 11.552 3.273 L 12.501 3.587 Z",
    fill: "rgb(25,37,61)",
    fillRule: "nonzero"
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062C\u0627\u0631\u06CC")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 986,
    height: 63,
    viewBox: "0 0 986 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 986,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 89.495 11.272 L 89.045 11.49 L 89.495 11.272 Z M 80.228 2.005 L 80.446 1.555 L 80.228 2.005 Z M 102.772 60.995 L 102.554 61.445 L 102.772 60.995 Z M 93.505 51.728 L 93.055 51.946 L 93.505 51.728 Z M 980.142 57.142 L 980.496 57.496 L 980.142 57.142 Z M 986 0 L 985.5 0 L 985.5 23 L 986 23 L 986.5 23 L 986.5 0 L 986 0 Z M 946 63 L 946 62.5 L 123 62.5 L 123 63 L 123 63.5 L 946 63.5 L 946 63 Z M 60 0 L 60 -0.5 L 0 -0.5 L 0 0 L 0 0.5 L 60 0.5 L 60 0 Z M 91.5 31.5 L 92 31.5 C 92 26.143 92 22.097 91.748 18.873 C 91.495 15.645 90.986 13.2 89.945 11.054 L 89.495 11.272 L 89.045 11.49 C 90.009 13.477 90.503 15.782 90.751 18.952 C 91 22.127 91 26.127 91 31.5 L 91.5 31.5 Z M 60 0 L 60 0.5 C 65.373 0.5 69.373 0.5 72.548 0.749 C 75.718 0.997 78.023 1.491 80.01 2.455 L 80.228 2.005 L 80.446 1.555 C 78.3 0.514 75.855 0.005 72.627 -0.248 C 69.403 -0.5 65.357 -0.5 60 -0.5 L 60 0 Z M 89.495 11.272 L 89.945 11.054 C 87.935 6.911 84.589 3.565 80.446 1.555 L 80.228 2.005 L 80.01 2.455 C 83.951 4.366 87.134 7.549 89.045 11.49 L 89.495 11.272 Z M 123 63 L 123 62.5 C 117.627 62.5 113.627 62.5 110.452 62.251 C 107.282 62.003 104.977 61.509 102.99 60.545 L 102.772 60.995 L 102.554 61.445 C 104.7 62.486 107.145 62.995 110.373 63.248 C 113.597 63.5 117.643 63.5 123 63.5 L 123 63 Z M 91.5 31.5 L 91 31.5 C 91 36.857 91 40.903 91.252 44.127 C 91.505 47.355 92.014 49.8 93.055 51.946 L 93.505 51.728 L 93.955 51.51 C 92.991 49.523 92.497 47.218 92.249 44.048 C 92 40.873 92 36.873 92 31.5 L 91.5 31.5 Z M 102.772 60.995 L 102.99 60.545 C 99.049 58.634 95.866 55.451 93.955 51.51 L 93.505 51.728 L 93.055 51.946 C 95.065 56.089 98.411 59.435 102.554 61.445 L 102.772 60.995 Z M 986 23 L 985.5 23 C 985.5 32.442 985.499 39.474 984.772 44.879 C 984.047 50.27 982.609 53.968 979.789 56.789 L 980.142 57.142 L 980.496 57.496 C 983.533 54.458 985.024 50.514 985.763 45.012 C 986.501 39.525 986.5 32.414 986.5 23 L 986 23 Z M 946 63 L 946 63.5 C 955.414 63.5 962.525 63.501 968.012 62.763 C 973.514 62.024 977.458 60.533 980.496 57.496 L 980.142 57.142 L 979.789 56.789 C 976.968 59.609 973.27 61.047 967.879 61.772 C 962.474 62.499 955.442 62.5 946 62.5 L 946 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 25,
      top: 12,
      width: 119,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 146,
      top: 0,
      width: 78,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 11.667,
    height: 15,
    viewBox: "0 0 11.667 15",
    fill: "none",
    style: {
      position: "absolute",
      left: 4.166,
      top: 2.5,
      width: 11.667,
      height: 15,
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 9.472 14.8 L 9.096 15.449 L 9.096 15.449 L 9.472 14.8 Z M 6.816 13.263 L 7.191 12.614 L 7.191 12.614 L 6.816 13.263 Z M 4.851 13.263 L 5.227 13.912 L 5.227 13.912 L 4.851 13.263 Z M 2.195 14.8 L 1.819 14.151 L 1.819 14.151 L 2.195 14.8 Z M 3.134 0.263 L 2.989 -0.473 L 3.134 0.263 Z M 8.533 0.263 L 8.388 0.999 L 8.388 0.999 L 8.533 0.263 Z M 11.667 4.037 L 10.917 4.037 L 10.917 13.556 L 11.667 13.556 L 12.417 13.556 L 12.417 4.037 L 11.667 4.037 Z M 0 13.556 L 0.75 13.556 L 0.75 4.037 L 0 4.037 L -0.75 4.037 L -0.75 13.556 L 0 13.556 Z M 9.472 14.8 L 9.847 14.151 L 7.191 12.614 L 6.816 13.263 L 6.44 13.912 L 9.096 15.449 L 9.472 14.8 Z M 4.851 13.263 L 4.475 12.614 L 1.819 14.151 L 2.195 14.8 L 2.571 15.449 L 5.227 13.912 L 4.851 13.263 Z M 3.134 0.263 L 3.279 0.999 C 4.965 0.667 6.701 0.667 8.388 0.999 L 8.533 0.263 L 8.677 -0.473 C 6.8 -0.842 4.867 -0.842 2.989 -0.473 L 3.134 0.263 Z M 6.816 13.263 L 7.191 12.614 C 6.352 12.128 5.314 12.128 4.475 12.614 L 4.851 13.263 L 5.227 13.912 C 5.601 13.696 6.066 13.696 6.44 13.912 L 6.816 13.263 Z M 0 13.556 L -0.75 13.556 C -0.75 15.262 1.112 16.294 2.571 15.449 L 2.195 14.8 L 1.819 14.151 C 1.334 14.432 0.75 14.077 0.75 13.556 L 0 13.556 Z M 11.667 13.556 L 10.917 13.556 C 10.917 14.077 10.332 14.432 9.847 14.151 L 9.472 14.8 L 9.096 15.449 C 10.555 16.294 12.417 15.262 12.417 13.556 L 11.667 13.556 Z M 11.667 4.037 L 12.417 4.037 C 12.417 1.837 10.845 -0.047 8.677 -0.473 L 8.533 0.263 L 8.388 0.999 C 9.864 1.289 10.917 2.566 10.917 4.037 L 11.667 4.037 Z M 0 4.037 L 0.75 4.037 C 0.75 2.566 1.803 1.289 3.279 0.999 L 3.134 0.263 L 2.989 -0.473 C 0.822 -0.047 -0.75 1.837 -0.75 4.037 L 0 4.037 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 7,
      height: 48,
      borderRadius: 12,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 16px 12px 16px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0647\u0645\u0647 \u0645\u062D\u0635\u0648\u0644\u0627\u062A"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-a3413780eb2b49c8",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-38178af60c34ce30",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-ff69e331165e3819",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 5px 7px 6px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "58")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 15.334,
      fontFamily: "IRANSansXFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      lineHeight: 1,
      color: "rgb(230,233,243)",
      flexShrink: 0
    }
  }, "..."), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "3")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "2")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "1"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 89,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, " \u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:423 Dashboard
function Dashboard2(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1159,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 926,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 986,
    height: 63,
    viewBox: "0 0 986 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 986,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 46.995 11.272 L 46.545 11.49 L 46.995 11.272 Z M 37.728 2.005 L 37.51 2.455 L 37.728 2.005 Z M 60.272 60.995 L 60.054 61.445 L 60.272 60.995 Z M 51.005 51.728 L 50.555 51.946 L 51.005 51.728 Z M 980.142 57.142 L 980.496 57.496 L 980.142 57.142 Z M 986 0 L 985.5 0 L 985.5 23 L 986 23 L 986.5 23 L 986.5 0 L 986 0 Z M 946 63 L 946 62.5 L 80.5 62.5 L 80.5 63 L 80.5 63.5 L 946 63.5 L 946 63 Z M 17.5 0 L 17.5 -0.5 L 0 -0.5 L 0 0 L 0 0.5 L 17.5 0.5 L 17.5 0 Z M 49 31.5 L 49.5 31.5 C 49.5 26.143 49.5 22.097 49.248 18.873 C 48.995 15.645 48.486 13.2 47.445 11.054 L 46.995 11.272 L 46.545 11.49 C 47.509 13.477 48.003 15.782 48.251 18.952 C 48.5 22.127 48.5 26.127 48.5 31.5 L 49 31.5 Z M 17.5 0 L 17.5 0.5 C 22.873 0.5 26.873 0.5 30.048 0.749 C 33.218 0.997 35.523 1.491 37.51 2.455 L 37.728 2.005 L 37.946 1.555 C 35.8 0.514 33.355 0.005 30.127 -0.248 C 26.903 -0.5 22.857 -0.5 17.5 -0.5 L 17.5 0 Z M 46.995 11.272 L 47.445 11.054 C 45.435 6.911 42.089 3.565 37.946 1.555 L 37.728 2.005 L 37.51 2.455 C 41.451 4.366 44.634 7.549 46.545 11.49 L 46.995 11.272 Z M 80.5 63 L 80.5 62.5 C 75.127 62.5 71.127 62.5 67.952 62.251 C 64.782 62.003 62.477 61.509 60.49 60.545 L 60.272 60.995 L 60.054 61.445 C 62.2 62.486 64.645 62.995 67.873 63.248 C 71.097 63.5 75.143 63.5 80.5 63.5 L 80.5 63 Z M 49 31.5 L 48.5 31.5 C 48.5 36.857 48.5 40.903 48.752 44.127 C 49.005 47.355 49.514 49.8 50.555 51.946 L 51.005 51.728 L 51.455 51.51 C 50.491 49.523 49.997 47.218 49.749 44.048 C 49.5 40.873 49.5 36.873 49.5 31.5 L 49 31.5 Z M 60.272 60.995 L 60.49 60.545 C 56.549 58.634 53.366 55.451 51.455 51.51 L 51.005 51.728 L 50.555 51.946 C 52.565 56.089 55.911 59.435 60.054 61.445 L 60.272 60.995 Z M 986 23 L 985.5 23 C 985.5 32.442 985.499 39.474 984.772 44.879 C 984.047 50.27 982.609 53.968 979.789 56.789 L 980.142 57.142 L 980.496 57.496 C 983.533 54.458 985.024 50.514 985.763 45.012 C 986.501 39.525 986.5 32.414 986.5 23 L 986 23 Z M 946 63 L 946 63.5 C 955.414 63.5 962.525 63.501 968.012 62.763 C 973.514 62.024 977.458 60.533 980.496 57.496 L 980.142 57.142 L 979.789 56.789 C 976.968 59.609 973.27 61.047 967.879 61.772 C 962.474 62.499 955.442 62.5 946 62.5 L 946 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 826,
      top: 0,
      display: "flex",
      flexDirection: "row",
      gap: 4,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      letterSpacing: "-0.050em",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0644\u0646\u0627\u0632 \u062D\u0633\u06CC\u0646\u06CC"), /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-352b67f25f17040b",
    style: {
      position: "relative",
      width: 60,
      height: 60,
      borderRadius: "50%",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      flexShrink: 0
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 7,
      width: 40,
      height: 40
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 539,
      borderRadius: 40,
      background: "linear-gradient(117.869deg, rgb(239,240,244) 4.15%, rgb(255,255,255) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(244,245,249), 0px 0px 80px 0px rgba(0,0,0,0.08)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 756,
      display: "flex",
      flexDirection: "column",
      gap: 48,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0646\u0627\u0645 \u062E\u0627\u0646\u0648\u0627\u062F\u06AF\u06CC"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0627\u0645 \u062E\u0627\u0646\u0648\u0627\u062F\u06AF\u06CC \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F."))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0646\u0627\u0645"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0627\u0645 \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F.")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u06CC\u0645\u06CC\u0644"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u06CC\u0645\u06CC\u0644 \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F."))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0647\u0645\u0631\u0627\u0647"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0647\u0645\u0631\u0627\u0647 \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F.")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u062A\u06A9\u0631\u0627\u0631 \u0631\u0645\u0632 \u0639\u0628\u0648\u0631 "), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0631\u0645\u0632 \u0639\u0628\u0648\u0631 \u062C\u062F\u06CC\u062F \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F."))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0631\u0645\u0632 \u0639\u0628\u0648\u0631"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0631\u0645\u0632 \u0639\u0628\u0648\u0631 \u062E\u0648\u062F \u0631\u0627 \u0648\u0627\u0631\u062F \u06A9\u0646\u06CC\u062F."))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647")))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 109,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 89,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, " \u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:177 Linear / Electronic, Devices / Printer
function LinearElectronicDevicesPrinter(_p = {}) {
  return __figIcon(_p, 20, "rgb(255,255,255)", 1.25, ["M5 15H3.333a1.667 1.667 0 0 1-1.666-1.667V6.667A1.667 1.667 0 0 1 3.333 5h13.334a1.667 1.667 0 0 1 1.666 1.667v6.666A1.667 1.667 0 0 1 16.667 15H15","M5 5V3.333c0-.92.746-1.666 1.667-1.666h6.666c.92 0 1.667.746 1.667 1.666V5","M5 11.667h10v5c0 .92-.746 1.666-1.667 1.666H6.667c-.92 0-1.667-.746-1.667-1.666z","M7.5 14.167h5M7.5 16.25h3","M4.167 8.333h2.5"], [["circle",{cx:14.167,cy:8.333,r:0.833,fill:"currentColor",stroke:"none"}]]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 20,
      height: 20,
      overflow: "hidden",
      position: "relative",
      color: "rgb(255,255,255)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1.667,
      top: 5,
      width: 16.667,
      height: 9.986,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 2.5,
      height: 1.5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 11.667,
      height: 1.5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 5,
      top: 1.667,
      width: 10,
      height: 16.667,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 13.333,
      top: 7.5,
      width: 1.667,
      height: 1.667,
      borderRadius: "50%",
      backgroundColor: "rgb(255,255,255)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 5,
      height: 1.5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 3.333,
      height: 1.5,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"));
}

// figma node: 1:505 Dashboard
function Dashboard3(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1138,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 872,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0644\u063A\u0648 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0645\u0631\u062C\u0648\u0639 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCheck, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062A\u062D\u0648\u06CC\u0644 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062C\u0627\u0631\u06CC")), /*#__PURE__*/React.createElement("svg", {
    width: 12.767,
    height: 12.763,
    viewBox: "0 0 12.767 12.763",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(-0.970,-0.244,-0.244,0.970,182.500,103.121)",
      transformOrigin: "0 0",
      width: 12.767,
      height: 12.763,
      filter: "drop-shadow(0px 4px 4px rgba(0,0,0,0.25))",
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 3.078 2.296 C -1.026 3.668 -1.026 5.906 3.078 7.272 L 4.889 7.873 L 5.491 9.685 C 6.856 13.788 9.101 13.788 10.466 9.685 L 12.501 3.587 C 13.407 0.849 11.92 -0.645 9.182 0.268 Z",
    fill: "rgb(255,255,255)",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 9.497 1.217 L 9.498 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.762 1.347 L 2.76 1.348 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.22 L 2.762 8.221 L 3.078 7.272 Z M 4.889 7.873 L 5.838 7.558 L 5.68 7.082 L 5.204 6.924 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10 L 4.542 10.001 L 5.491 9.685 Z M 10.466 9.685 L 9.518 9.368 L 9.518 9.369 L 10.466 9.685 Z M 12.501 3.587 L 13.45 3.904 L 13.451 3.901 L 12.501 3.587 Z M 9.182 0.268 L 8.867 -0.681 L 2.762 1.347 L 3.078 2.296 L 3.393 3.245 L 9.497 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.76 1.348 C 1.671 1.712 0.775 2.158 0.132 2.69 C -0.507 3.218 -1 3.925 -1 4.786 C -1 5.648 -0.507 6.354 0.133 6.882 C 0.776 7.413 1.672 7.858 2.762 8.22 L 3.078 7.272 L 3.393 6.323 C 2.432 6.003 1.789 5.656 1.406 5.34 C 1.02 5.021 1 4.827 1 4.786 C 1 4.746 1.02 4.551 1.407 4.231 C 1.79 3.914 2.433 3.566 3.395 3.245 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.221 L 4.574 8.822 L 4.889 7.873 L 5.204 6.924 L 3.393 6.323 L 3.078 7.272 Z M 4.889 7.873 L 3.94 8.188 L 4.542 10 L 5.491 9.685 L 6.44 9.37 L 5.838 7.558 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10.001 C 4.905 11.091 5.35 11.987 5.881 12.63 C 6.41 13.27 7.117 13.763 7.979 13.763 C 8.84 13.763 9.547 13.27 10.076 12.63 C 10.607 11.987 11.052 11.091 11.415 10.001 L 10.466 9.685 L 9.518 9.369 C 9.198 10.331 8.85 10.974 8.534 11.356 C 8.215 11.742 8.02 11.763 7.979 11.763 C 7.937 11.763 7.742 11.742 7.423 11.356 C 7.107 10.974 6.76 10.331 6.44 9.369 L 5.491 9.685 Z M 10.466 9.685 L 11.415 10.002 L 13.45 3.904 L 12.501 3.587 L 11.553 3.271 L 9.518 9.368 L 10.466 9.685 Z M 12.501 3.587 L 13.451 3.901 C 13.954 2.379 13.908 0.846 12.916 -0.148 C 11.922 -1.143 10.389 -1.188 8.866 -0.681 L 9.182 0.268 L 9.498 1.217 C 10.713 0.812 11.292 1.057 11.5 1.265 C 11.71 1.475 11.954 2.057 11.552 3.273 L 12.501 3.587 Z",
    fill: "rgb(25,37,61)",
    fillRule: "nonzero"
  })))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 20,
      top: 12,
      width: 124,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062C\u0627\u0631\u06CC"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 146,
      top: 0,
      width: 78,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 744,
      height: 200,
      display: "flex",
      flexDirection: "column",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648\u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u062A\u06CC\u0633\u062A\u0648 \u0645\u062F\u0644 Viola \u062D\u062C\u0645 120 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearElectronicDevicesPrinter, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 190,
      borderRadius: 16,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 174,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 86,
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 22,
    height: 9,
    viewBox: "0 0 22 9",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,0,25)",
      transformOrigin: "0 0",
      width: 22,
      height: 9,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 18.719 6.901 C 18.719 7.324 18.634 7.648 18.463 7.874 C 18.292 8.099 18.036 8.276 17.695 8.406 C 17.36 8.536 16.798 8.71 16.01 8.928 L 15.754 9 L 15.438 7.802 C 16.023 7.652 16.45 7.536 16.719 7.454 C 16.995 7.372 17.212 7.287 17.369 7.198 C 17.534 7.109 17.616 7.01 17.616 6.901 L 17.616 6.85 L 16.709 6.85 C 16.112 6.85 15.635 6.666 15.281 6.297 C 14.926 5.928 14.749 5.416 14.749 4.761 C 14.749 4.297 14.831 3.884 14.995 3.522 C 15.166 3.154 15.399 2.867 15.695 2.662 C 15.99 2.457 16.325 2.355 16.7 2.355 C 17.107 2.355 17.461 2.457 17.764 2.662 C 18.072 2.86 18.309 3.143 18.473 3.512 C 18.644 3.874 18.729 4.29 18.729 4.761 L 18.729 5.56 L 20.03 5.56 C 20.614 5.56 20.906 5.362 20.906 4.966 C 20.906 4.659 20.814 4.051 20.631 3.143 L 21.665 2.867 C 21.888 3.87 22 4.577 22 4.986 C 22 5.314 21.928 5.621 21.783 5.908 C 21.645 6.188 21.432 6.416 21.143 6.594 C 20.86 6.765 20.512 6.85 20.099 6.85 L 18.719 6.85 L 18.719 6.901 Z M 0 4.587 C 0 4.157 0.066 3.713 0.197 3.256 C 0.328 2.799 0.493 2.375 0.69 1.986 L 1.606 2.498 C 1.251 3.27 1.074 3.966 1.074 4.587 C 1.074 5.154 1.202 5.59 1.458 5.898 C 1.721 6.198 2.141 6.348 2.719 6.348 L 3.626 6.348 C 4.039 6.348 4.358 6.307 4.581 6.225 C 4.805 6.143 4.962 6.01 5.054 5.826 C 5.146 5.635 5.192 5.369 5.192 5.027 L 5.192 1.587 C 5.192 0.529 5.688 0 6.68 0 L 6.995 0 C 7.494 0 7.865 0.14 8.108 0.42 C 8.351 0.7 8.473 1.085 8.473 1.577 L 8.483 4.73 C 8.483 5.024 8.499 5.235 8.532 5.365 C 8.565 5.488 8.637 5.573 8.749 5.621 C 8.86 5.669 9.044 5.693 9.3 5.693 L 9.567 5.693 C 9.704 5.693 9.813 5.659 9.892 5.59 C 9.97 5.522 10.026 5.413 10.059 5.263 L 10.335 4.218 C 10.486 3.645 10.726 3.205 11.054 2.898 C 11.383 2.584 11.777 2.427 12.236 2.427 C 12.604 2.427 12.929 2.532 13.212 2.744 C 13.501 2.956 13.724 3.246 13.882 3.614 C 14.046 3.983 14.128 4.396 14.128 4.853 C 14.128 5.392 14.046 5.836 13.882 6.184 C 13.718 6.532 13.511 6.785 13.261 6.942 C 13.011 7.099 12.749 7.177 12.473 7.177 C 12.243 7.177 12 7.13 11.744 7.034 C 11.494 6.939 11.123 6.765 10.631 6.512 C 10.486 6.662 10.322 6.778 10.138 6.86 C 9.954 6.942 9.77 6.983 9.586 6.983 L 9.291 6.983 C 8.023 6.983 7.389 6.232 7.389 4.73 L 7.379 1.659 C 7.379 1.529 7.35 1.437 7.291 1.382 C 7.238 1.321 7.136 1.29 6.985 1.29 L 6.69 1.29 C 6.532 1.29 6.424 1.317 6.365 1.372 C 6.312 1.427 6.286 1.522 6.286 1.659 L 6.286 5.027 C 6.292 5.833 6.076 6.471 5.635 6.942 C 5.202 7.406 4.532 7.638 3.626 7.638 L 2.719 7.638 C 2.141 7.638 1.649 7.502 1.241 7.229 C 0.834 6.949 0.525 6.577 0.315 6.113 C 0.105 5.648 0 5.14 0 4.587 Z M 2.798 1.382 L 3.99 1.382 L 3.99 2.611 L 2.798 2.611 L 2.798 1.382 Z M 11.153 5.406 C 11.54 5.604 11.823 5.741 12 5.816 C 12.184 5.884 12.355 5.918 12.512 5.918 C 12.696 5.918 12.837 5.84 12.936 5.683 C 13.041 5.519 13.094 5.242 13.094 4.853 C 13.094 4.478 13.018 4.188 12.867 3.983 C 12.716 3.778 12.509 3.676 12.246 3.676 C 12.049 3.676 11.872 3.754 11.714 3.911 C 11.563 4.061 11.448 4.283 11.369 4.577 L 11.153 5.406 Z M 15.803 4.72 C 15.803 5.027 15.875 5.253 16.02 5.396 C 16.164 5.532 16.394 5.601 16.709 5.601 L 17.635 5.601 L 17.635 4.741 C 17.635 4.379 17.56 4.096 17.409 3.891 C 17.258 3.686 17.021 3.584 16.7 3.584 C 16.411 3.584 16.187 3.689 16.03 3.901 C 15.878 4.106 15.803 4.379 15.803 4.72 Z M 18.69 0.666 L 19.882 0.666 L 19.921 0.666 L 21.113 0.666 L 21.113 1.741 L 19.921 1.741 L 19.882 1.741 L 18.69 1.741 L 18.69 0.666 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 18,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 57,
      display: "flex",
      flexDirection: "column",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 16,
      borderRadius: 17,
      backgroundColor: "rgb(227,19,54)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "0px 4px 0px 4px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "12%"))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 180,
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:615 Dashboard
function Dashboard4(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1271,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 987,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0644\u063A\u0648 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCross, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u0645\u0631\u062C\u0648\u0639 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(47,47,51)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartCheck, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062A\u062D\u0648\u06CC\u0644 \u0634\u062F\u0647"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 202,
      height: 112,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 202,
    height: 112,
    viewBox: "0 0 202 112",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 202,
      height: 112,
      borderRadius: 40,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 35,
      top: 8,
      width: 132,
      display: "flex",
      flexDirection: "column",
      gap: 12,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062C\u0627\u0631\u06CC")), /*#__PURE__*/React.createElement("svg", {
    width: 12.767,
    height: 12.763,
    viewBox: "0 0 12.767 12.763",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(-0.970,-0.244,-0.244,0.970,182.500,103.121)",
      transformOrigin: "0 0",
      width: 12.767,
      height: 12.763,
      filter: "drop-shadow(0px 4px 4px rgba(0,0,0,0.25))",
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 3.078 2.296 C -1.026 3.668 -1.026 5.906 3.078 7.272 L 4.889 7.873 L 5.491 9.685 C 6.856 13.788 9.101 13.788 10.466 9.685 L 12.501 3.587 C 13.407 0.849 11.92 -0.645 9.182 0.268 Z",
    fill: "rgb(255,255,255)",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 9.182 0.268 L 9.497 1.217 L 9.498 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.762 1.347 L 2.76 1.348 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.22 L 2.762 8.221 L 3.078 7.272 Z M 4.889 7.873 L 5.838 7.558 L 5.68 7.082 L 5.204 6.924 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10 L 4.542 10.001 L 5.491 9.685 Z M 10.466 9.685 L 9.518 9.368 L 9.518 9.369 L 10.466 9.685 Z M 12.501 3.587 L 13.45 3.904 L 13.451 3.901 L 12.501 3.587 Z M 9.182 0.268 L 8.867 -0.681 L 2.762 1.347 L 3.078 2.296 L 3.393 3.245 L 9.497 1.217 L 9.182 0.268 Z M 3.078 2.296 L 2.76 1.348 C 1.671 1.712 0.775 2.158 0.132 2.69 C -0.507 3.218 -1 3.925 -1 4.786 C -1 5.648 -0.507 6.354 0.133 6.882 C 0.776 7.413 1.672 7.858 2.762 8.22 L 3.078 7.272 L 3.393 6.323 C 2.432 6.003 1.789 5.656 1.406 5.34 C 1.02 5.021 1 4.827 1 4.786 C 1 4.746 1.02 4.551 1.407 4.231 C 1.79 3.914 2.433 3.566 3.395 3.245 L 3.078 2.296 Z M 3.078 7.272 L 2.762 8.221 L 4.574 8.822 L 4.889 7.873 L 5.204 6.924 L 3.393 6.323 L 3.078 7.272 Z M 4.889 7.873 L 3.94 8.188 L 4.542 10 L 5.491 9.685 L 6.44 9.37 L 5.838 7.558 L 4.889 7.873 Z M 5.491 9.685 L 4.542 10.001 C 4.905 11.091 5.35 11.987 5.881 12.63 C 6.41 13.27 7.117 13.763 7.979 13.763 C 8.84 13.763 9.547 13.27 10.076 12.63 C 10.607 11.987 11.052 11.091 11.415 10.001 L 10.466 9.685 L 9.518 9.369 C 9.198 10.331 8.85 10.974 8.534 11.356 C 8.215 11.742 8.02 11.763 7.979 11.763 C 7.937 11.763 7.742 11.742 7.423 11.356 C 7.107 10.974 6.76 10.331 6.44 9.369 L 5.491 9.685 Z M 10.466 9.685 L 11.415 10.002 L 13.45 3.904 L 12.501 3.587 L 11.553 3.271 L 9.518 9.368 L 10.466 9.685 Z M 12.501 3.587 L 13.451 3.901 C 13.954 2.379 13.908 0.846 12.916 -0.148 C 11.922 -1.143 10.389 -1.188 8.866 -0.681 L 9.182 0.268 L 9.498 1.217 C 10.713 0.812 11.292 1.057 11.5 1.265 C 11.71 1.475 11.954 2.057 11.552 3.273 L 12.501 3.587 Z",
    fill: "rgb(25,37,61)",
    fillRule: "nonzero"
  })))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 20,
      top: 12,
      width: 124,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0645\u062D\u0635\u0648\u0644\u0627\u062A \u062C\u0627\u0631\u06CC"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 146,
      top: 0,
      width: 78,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 744,
      height: 200,
      display: "flex",
      flexDirection: "column",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648\u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u062A\u06CC\u0633\u062A\u0648 \u0645\u062F\u0644 Viola \u062D\u062C\u0645 120 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearElectronicDevicesPrinter, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 190,
      borderRadius: 16,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 174,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 86,
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 22,
    height: 9,
    viewBox: "0 0 22 9",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,0,25)",
      transformOrigin: "0 0",
      width: 22,
      height: 9,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 18.719 6.901 C 18.719 7.324 18.634 7.648 18.463 7.874 C 18.292 8.099 18.036 8.276 17.695 8.406 C 17.36 8.536 16.798 8.71 16.01 8.928 L 15.754 9 L 15.438 7.802 C 16.023 7.652 16.45 7.536 16.719 7.454 C 16.995 7.372 17.212 7.287 17.369 7.198 C 17.534 7.109 17.616 7.01 17.616 6.901 L 17.616 6.85 L 16.709 6.85 C 16.112 6.85 15.635 6.666 15.281 6.297 C 14.926 5.928 14.749 5.416 14.749 4.761 C 14.749 4.297 14.831 3.884 14.995 3.522 C 15.166 3.154 15.399 2.867 15.695 2.662 C 15.99 2.457 16.325 2.355 16.7 2.355 C 17.107 2.355 17.461 2.457 17.764 2.662 C 18.072 2.86 18.309 3.143 18.473 3.512 C 18.644 3.874 18.729 4.29 18.729 4.761 L 18.729 5.56 L 20.03 5.56 C 20.614 5.56 20.906 5.362 20.906 4.966 C 20.906 4.659 20.814 4.051 20.631 3.143 L 21.665 2.867 C 21.888 3.87 22 4.577 22 4.986 C 22 5.314 21.928 5.621 21.783 5.908 C 21.645 6.188 21.432 6.416 21.143 6.594 C 20.86 6.765 20.512 6.85 20.099 6.85 L 18.719 6.85 L 18.719 6.901 Z M 0 4.587 C 0 4.157 0.066 3.713 0.197 3.256 C 0.328 2.799 0.493 2.375 0.69 1.986 L 1.606 2.498 C 1.251 3.27 1.074 3.966 1.074 4.587 C 1.074 5.154 1.202 5.59 1.458 5.898 C 1.721 6.198 2.141 6.348 2.719 6.348 L 3.626 6.348 C 4.039 6.348 4.358 6.307 4.581 6.225 C 4.805 6.143 4.962 6.01 5.054 5.826 C 5.146 5.635 5.192 5.369 5.192 5.027 L 5.192 1.587 C 5.192 0.529 5.688 0 6.68 0 L 6.995 0 C 7.494 0 7.865 0.14 8.108 0.42 C 8.351 0.7 8.473 1.085 8.473 1.577 L 8.483 4.73 C 8.483 5.024 8.499 5.235 8.532 5.365 C 8.565 5.488 8.637 5.573 8.749 5.621 C 8.86 5.669 9.044 5.693 9.3 5.693 L 9.567 5.693 C 9.704 5.693 9.813 5.659 9.892 5.59 C 9.97 5.522 10.026 5.413 10.059 5.263 L 10.335 4.218 C 10.486 3.645 10.726 3.205 11.054 2.898 C 11.383 2.584 11.777 2.427 12.236 2.427 C 12.604 2.427 12.929 2.532 13.212 2.744 C 13.501 2.956 13.724 3.246 13.882 3.614 C 14.046 3.983 14.128 4.396 14.128 4.853 C 14.128 5.392 14.046 5.836 13.882 6.184 C 13.718 6.532 13.511 6.785 13.261 6.942 C 13.011 7.099 12.749 7.177 12.473 7.177 C 12.243 7.177 12 7.13 11.744 7.034 C 11.494 6.939 11.123 6.765 10.631 6.512 C 10.486 6.662 10.322 6.778 10.138 6.86 C 9.954 6.942 9.77 6.983 9.586 6.983 L 9.291 6.983 C 8.023 6.983 7.389 6.232 7.389 4.73 L 7.379 1.659 C 7.379 1.529 7.35 1.437 7.291 1.382 C 7.238 1.321 7.136 1.29 6.985 1.29 L 6.69 1.29 C 6.532 1.29 6.424 1.317 6.365 1.372 C 6.312 1.427 6.286 1.522 6.286 1.659 L 6.286 5.027 C 6.292 5.833 6.076 6.471 5.635 6.942 C 5.202 7.406 4.532 7.638 3.626 7.638 L 2.719 7.638 C 2.141 7.638 1.649 7.502 1.241 7.229 C 0.834 6.949 0.525 6.577 0.315 6.113 C 0.105 5.648 0 5.14 0 4.587 Z M 2.798 1.382 L 3.99 1.382 L 3.99 2.611 L 2.798 2.611 L 2.798 1.382 Z M 11.153 5.406 C 11.54 5.604 11.823 5.741 12 5.816 C 12.184 5.884 12.355 5.918 12.512 5.918 C 12.696 5.918 12.837 5.84 12.936 5.683 C 13.041 5.519 13.094 5.242 13.094 4.853 C 13.094 4.478 13.018 4.188 12.867 3.983 C 12.716 3.778 12.509 3.676 12.246 3.676 C 12.049 3.676 11.872 3.754 11.714 3.911 C 11.563 4.061 11.448 4.283 11.369 4.577 L 11.153 5.406 Z M 15.803 4.72 C 15.803 5.027 15.875 5.253 16.02 5.396 C 16.164 5.532 16.394 5.601 16.709 5.601 L 17.635 5.601 L 17.635 4.741 C 17.635 4.379 17.56 4.096 17.409 3.891 C 17.258 3.686 17.021 3.584 16.7 3.584 C 16.411 3.584 16.187 3.689 16.03 3.901 C 15.878 4.106 15.803 4.379 15.803 4.72 Z M 18.69 0.666 L 19.882 0.666 L 19.921 0.666 L 21.113 0.666 L 21.113 1.741 L 19.921 1.741 L 19.882 1.741 L 18.69 1.741 L 18.69 0.666 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 18,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 57,
      display: "flex",
      flexDirection: "column",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 16,
      borderRadius: 17,
      backgroundColor: "rgb(227,19,54)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "0px 4px 0px 4px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "12%"))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 180,
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 744,
      height: 200,
      display: "flex",
      flexDirection: "column",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648\u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u062A\u06CC\u0633\u062A\u0648 \u0645\u062F\u0644 Viola \u062D\u062C\u0645 120 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearElectronicDevicesPrinter, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 190,
      borderRadius: 16,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 174,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 86,
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 22,
    height: 9,
    viewBox: "0 0 22 9",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,-1,1,0,0,25)",
      transformOrigin: "0 0",
      width: 22,
      height: 9,
      color: "rgb(0,0,0)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 18.719 6.901 C 18.719 7.324 18.634 7.648 18.463 7.874 C 18.292 8.099 18.036 8.276 17.695 8.406 C 17.36 8.536 16.798 8.71 16.01 8.928 L 15.754 9 L 15.438 7.802 C 16.023 7.652 16.45 7.536 16.719 7.454 C 16.995 7.372 17.212 7.287 17.369 7.198 C 17.534 7.109 17.616 7.01 17.616 6.901 L 17.616 6.85 L 16.709 6.85 C 16.112 6.85 15.635 6.666 15.281 6.297 C 14.926 5.928 14.749 5.416 14.749 4.761 C 14.749 4.297 14.831 3.884 14.995 3.522 C 15.166 3.154 15.399 2.867 15.695 2.662 C 15.99 2.457 16.325 2.355 16.7 2.355 C 17.107 2.355 17.461 2.457 17.764 2.662 C 18.072 2.86 18.309 3.143 18.473 3.512 C 18.644 3.874 18.729 4.29 18.729 4.761 L 18.729 5.56 L 20.03 5.56 C 20.614 5.56 20.906 5.362 20.906 4.966 C 20.906 4.659 20.814 4.051 20.631 3.143 L 21.665 2.867 C 21.888 3.87 22 4.577 22 4.986 C 22 5.314 21.928 5.621 21.783 5.908 C 21.645 6.188 21.432 6.416 21.143 6.594 C 20.86 6.765 20.512 6.85 20.099 6.85 L 18.719 6.85 L 18.719 6.901 Z M 0 4.587 C 0 4.157 0.066 3.713 0.197 3.256 C 0.328 2.799 0.493 2.375 0.69 1.986 L 1.606 2.498 C 1.251 3.27 1.074 3.966 1.074 4.587 C 1.074 5.154 1.202 5.59 1.458 5.898 C 1.721 6.198 2.141 6.348 2.719 6.348 L 3.626 6.348 C 4.039 6.348 4.358 6.307 4.581 6.225 C 4.805 6.143 4.962 6.01 5.054 5.826 C 5.146 5.635 5.192 5.369 5.192 5.027 L 5.192 1.587 C 5.192 0.529 5.688 0 6.68 0 L 6.995 0 C 7.494 0 7.865 0.14 8.108 0.42 C 8.351 0.7 8.473 1.085 8.473 1.577 L 8.483 4.73 C 8.483 5.024 8.499 5.235 8.532 5.365 C 8.565 5.488 8.637 5.573 8.749 5.621 C 8.86 5.669 9.044 5.693 9.3 5.693 L 9.567 5.693 C 9.704 5.693 9.813 5.659 9.892 5.59 C 9.97 5.522 10.026 5.413 10.059 5.263 L 10.335 4.218 C 10.486 3.645 10.726 3.205 11.054 2.898 C 11.383 2.584 11.777 2.427 12.236 2.427 C 12.604 2.427 12.929 2.532 13.212 2.744 C 13.501 2.956 13.724 3.246 13.882 3.614 C 14.046 3.983 14.128 4.396 14.128 4.853 C 14.128 5.392 14.046 5.836 13.882 6.184 C 13.718 6.532 13.511 6.785 13.261 6.942 C 13.011 7.099 12.749 7.177 12.473 7.177 C 12.243 7.177 12 7.13 11.744 7.034 C 11.494 6.939 11.123 6.765 10.631 6.512 C 10.486 6.662 10.322 6.778 10.138 6.86 C 9.954 6.942 9.77 6.983 9.586 6.983 L 9.291 6.983 C 8.023 6.983 7.389 6.232 7.389 4.73 L 7.379 1.659 C 7.379 1.529 7.35 1.437 7.291 1.382 C 7.238 1.321 7.136 1.29 6.985 1.29 L 6.69 1.29 C 6.532 1.29 6.424 1.317 6.365 1.372 C 6.312 1.427 6.286 1.522 6.286 1.659 L 6.286 5.027 C 6.292 5.833 6.076 6.471 5.635 6.942 C 5.202 7.406 4.532 7.638 3.626 7.638 L 2.719 7.638 C 2.141 7.638 1.649 7.502 1.241 7.229 C 0.834 6.949 0.525 6.577 0.315 6.113 C 0.105 5.648 0 5.14 0 4.587 Z M 2.798 1.382 L 3.99 1.382 L 3.99 2.611 L 2.798 2.611 L 2.798 1.382 Z M 11.153 5.406 C 11.54 5.604 11.823 5.741 12 5.816 C 12.184 5.884 12.355 5.918 12.512 5.918 C 12.696 5.918 12.837 5.84 12.936 5.683 C 13.041 5.519 13.094 5.242 13.094 4.853 C 13.094 4.478 13.018 4.188 12.867 3.983 C 12.716 3.778 12.509 3.676 12.246 3.676 C 12.049 3.676 11.872 3.754 11.714 3.911 C 11.563 4.061 11.448 4.283 11.369 4.577 L 11.153 5.406 Z M 15.803 4.72 C 15.803 5.027 15.875 5.253 16.02 5.396 C 16.164 5.532 16.394 5.601 16.709 5.601 L 17.635 5.601 L 17.635 4.741 C 17.635 4.379 17.56 4.096 17.409 3.891 C 17.258 3.686 17.021 3.584 16.7 3.584 C 16.411 3.584 16.187 3.689 16.03 3.901 C 15.878 4.106 15.803 4.379 15.803 4.72 Z M 18.69 0.666 L 19.882 0.666 L 19.921 0.666 L 21.113 0.666 L 21.113 1.741 L 19.921 1.741 L 19.882 1.741 L 18.69 1.741 L 18.69 0.666 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 18,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 57,
      display: "flex",
      flexDirection: "column",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 16,
      borderRadius: 17,
      backgroundColor: "rgb(227,19,54)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "0px 4px 0px 4px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "12%"))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 180,
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))), /*#__PURE__*/React.createElement("svg", {
    width: 17.101,
    height: 17.095,
    viewBox: "0 0 17.101 17.095",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(-0.970,-0.244,-0.244,0.970,1035.762,881.181)",
      transformOrigin: "0 0",
      width: 17.101,
      height: 17.095,
      filter: "drop-shadow(0px 4px 4px rgba(0,0,0,0.25))",
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 12.299 0.359 L 4.122 3.076 C -1.374 4.914 -1.374 7.911 4.122 9.74 L 6.549 10.546 L 7.355 12.973 C 9.184 18.469 12.19 18.469 14.019 12.973 L 16.745 4.805 C 17.958 1.138 15.966 -0.863 12.299 0.359 Z",
    fill: "rgb(255,255,255)",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12.299 0.359 L 12.614 1.308 L 12.615 1.308 L 12.299 0.359 Z M 4.122 3.076 L 3.807 2.127 L 3.805 2.127 L 4.122 3.076 Z M 4.122 9.74 L 3.807 10.689 L 3.807 10.689 L 4.122 9.74 Z M 6.549 10.546 L 7.498 10.231 L 7.34 9.755 L 6.864 9.597 L 6.549 10.546 Z M 7.355 12.973 L 6.406 13.288 L 6.406 13.288 L 7.355 12.973 Z M 14.019 12.973 L 13.071 12.656 L 13.071 12.657 L 14.019 12.973 Z M 16.745 4.805 L 17.694 5.122 L 17.694 5.119 L 16.745 4.805 Z M 12.299 0.359 L 11.984 -0.59 L 3.807 2.127 L 4.122 3.076 L 4.438 4.025 L 12.614 1.308 L 12.299 0.359 Z M 4.122 3.076 L 3.805 2.127 C 2.367 2.608 1.21 3.189 0.393 3.864 C -0.42 4.537 -1 5.397 -1 6.411 C -1 7.426 -0.42 8.285 0.394 8.957 C 1.211 9.631 2.368 10.21 3.807 10.689 L 4.122 9.74 L 4.438 8.791 C 3.128 8.355 2.224 7.874 1.667 7.414 C 1.107 6.952 1 6.605 1 6.411 C 1 6.218 1.107 5.869 1.668 5.406 C 2.225 4.945 3.129 4.462 4.439 4.024 L 4.122 3.076 Z M 4.122 9.74 L 3.807 10.689 L 6.234 11.495 L 6.549 10.546 L 6.864 9.597 L 4.437 8.791 L 4.122 9.74 Z M 6.549 10.546 L 5.6 10.861 L 6.406 13.288 L 7.355 12.973 L 8.304 12.658 L 7.498 10.231 L 6.549 10.546 Z M 7.355 12.973 L 6.406 13.288 C 6.885 14.727 7.465 15.884 8.14 16.701 C 8.812 17.515 9.673 18.095 10.687 18.095 C 11.702 18.095 12.562 17.515 13.235 16.701 C 13.91 15.884 14.49 14.727 14.968 13.288 L 14.019 12.973 L 13.071 12.657 C 12.635 13.967 12.153 14.871 11.693 15.428 C 11.23 15.988 10.882 16.095 10.687 16.095 C 10.493 16.095 10.144 15.988 9.682 15.428 C 9.222 14.871 8.74 13.967 8.304 12.657 L 7.355 12.973 Z M 14.019 12.973 L 14.968 13.289 L 17.694 5.122 L 16.745 4.805 L 15.796 4.489 L 13.071 12.656 L 14.019 12.973 Z M 16.745 4.805 L 17.694 5.119 C 18.352 3.132 18.256 1.24 17.06 0.042 C 15.862 -1.157 13.97 -1.252 11.983 -0.59 L 12.299 0.359 L 12.615 1.308 C 14.295 0.748 15.232 1.042 15.645 1.455 C 16.058 1.869 16.352 2.811 15.796 4.491 L 16.745 4.805 Z",
    fill: "rgb(25,37,61)",
    fillRule: "nonzero"
  })));
}

// figma node: 1:754 Dashboard
function Dashboard5(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1355,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1209,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 67 62.5 L 67 63 L 67 63.5 L 91 63.5 L 91 63 Z M 67 63 L 67 62.5 L 0 62.5 L 0 63 L 0 63.5 L 67 63.5 L 67 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 25,
      top: 12,
      width: 119,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 146,
      top: 0,
      width: 78,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  })))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-a3413780eb2b49c8",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-38178af60c34ce30",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-ff69e331165e3819",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648\u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u062A\u06CC\u0633\u062A\u0648 \u0645\u062F\u0644 Viola \u062D\u062C\u0645 120 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 22,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 16,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-5d69704775688cf8",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "فلوئید ضد آفتاب رنگی آردن با SPF 50 حجم 75 میلی لیتر\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-75c6b8a5990fac57",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648 \u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u067E\u06CC\u0646\u06A9 \u0648\u06CC\u0698\u0648\u0627\u0644 \u0645\u062F\u0644 Poison Girl \u062D\u062C\u0645 100 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-38178af60c34ce30",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(COCOLineSaved, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 5px 7px 6px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "58")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 15.334,
      fontFamily: "IRANSansXFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      lineHeight: 1,
      color: "rgb(230,233,243)",
      flexShrink: 0
    }
  }, "..."), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "3")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "2")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "1"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 89,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, " \u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:1015 Dashboard
function Dashboard6(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1271,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 872,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      display: "flex",
      flexDirection: "row",
      gap: 2,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 124,
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 132,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u062C\u0632\u06CC\u06CC\u0627\u062A \u0645\u062D\u0635\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 78,
      height: 55,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 203,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement(LinearElectronicDevicesPrinter, {
    style: {
      position: "relative",
      width: 20,
      height: 20,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u067E\u0631\u06CC\u0646\u062A"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 957,
      height: 120,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0644\u06CC : 1743566788"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E : 123456789-021"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0641\u0631\u0633\u062A\u0646\u062F\u0647 : \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0622\u062F\u0631\u0633 : \u062A\u0647\u0631\u0627\u0646\u060C\u0642\u06CC\u0637\u0631\u06CC\u0647\u060C\u062E\u06CC\u0627\u0628\u0627\u0646 \u0628\u0647\u0627\u0631"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0647\u0632\u06CC\u0646\u0647 \u0628\u0633\u062A\u0647 \u0628\u0646\u062F\u06CC : \u06F18\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0628\u0644\u063A \u06A9\u0644 : \u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 957,
      height: 120,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0644\u06CC : 1743566788"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E : 123456789-021"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0641\u0631\u0633\u062A\u0646\u062F\u0647 : \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0622\u062F\u0631\u0633 : \u062A\u0647\u0631\u0627\u0646\u060C\u0642\u06CC\u0637\u0631\u06CC\u0647\u060C\u062E\u06CC\u0627\u0628\u0627\u0646 \u0628\u0647\u0627\u0631"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0647\u0632\u06CC\u0646\u0647 \u0628\u0633\u062A\u0647 \u0628\u0646\u062F\u06CC : \u06F18\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0628\u0644\u063A \u06A9\u0644 : \u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 957,
      height: 120,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0631\u0633\u0648\u0644\u0647 : KFH-249822"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u0627\u0631\u0633\u0627\u0644 : \u067E\u0633\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0632\u0645\u0627\u0646 \u062A\u062D\u0648\u06CC\u0644 : \u062F\u0648\u0634\u0646\u0628\u0647 7 \u0645\u0647\u06311405-\u0633\u0627\u0639\u062A13:13")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u062F \u0645\u0644\u06CC : 1743566788"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E : 123456789-021"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0641\u0631\u0633\u062A\u0646\u062F\u0647 : \u0627\u0644\u0627\u0646\u0648 \u0634\u0627\u067E")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0622\u062F\u0631\u0633 : \u062A\u0647\u0631\u0627\u0646\u060C\u0642\u06CC\u0637\u0631\u06CC\u0647\u060C\u062E\u06CC\u0627\u0628\u0627\u0646 \u0628\u0647\u0627\u0631"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0647\u0632\u06CC\u0646\u0647 \u0628\u0633\u062A\u0647 \u0628\u0646\u062F\u06CC : \u06F18\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.8,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 300,
      fontSize: 14,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0628\u0644\u063A \u06A9\u0644 : \u06F2,\u06F1\u06F6\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))), /*#__PURE__*/React.createElement("svg", {
    width: 17.101,
    height: 17.095,
    viewBox: "0 0 17.101 17.095",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(-0.970,-0.244,-0.244,0.970,1037.762,671.181)",
      transformOrigin: "0 0",
      width: 17.101,
      height: 17.095,
      filter: "drop-shadow(0px 4px 4px rgba(0,0,0,0.25))",
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 12.299 0.359 L 4.122 3.076 C -1.374 4.914 -1.374 7.911 4.122 9.74 L 6.549 10.546 L 7.355 12.973 C 9.184 18.469 12.19 18.469 14.019 12.973 L 16.745 4.805 C 17.958 1.138 15.966 -0.863 12.299 0.359 Z",
    fill: "rgb(255,255,255)",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12.299 0.359 L 12.614 1.308 L 12.615 1.308 L 12.299 0.359 Z M 4.122 3.076 L 3.807 2.127 L 3.805 2.127 L 4.122 3.076 Z M 4.122 9.74 L 3.807 10.689 L 3.807 10.689 L 4.122 9.74 Z M 6.549 10.546 L 7.498 10.231 L 7.34 9.755 L 6.864 9.597 L 6.549 10.546 Z M 7.355 12.973 L 6.406 13.288 L 6.406 13.288 L 7.355 12.973 Z M 14.019 12.973 L 13.071 12.656 L 13.071 12.657 L 14.019 12.973 Z M 16.745 4.805 L 17.694 5.122 L 17.694 5.119 L 16.745 4.805 Z M 12.299 0.359 L 11.984 -0.59 L 3.807 2.127 L 4.122 3.076 L 4.438 4.025 L 12.614 1.308 L 12.299 0.359 Z M 4.122 3.076 L 3.805 2.127 C 2.367 2.608 1.21 3.189 0.393 3.864 C -0.42 4.537 -1 5.397 -1 6.411 C -1 7.426 -0.42 8.285 0.394 8.957 C 1.211 9.631 2.368 10.21 3.807 10.689 L 4.122 9.74 L 4.438 8.791 C 3.128 8.355 2.224 7.874 1.667 7.414 C 1.107 6.952 1 6.605 1 6.411 C 1 6.218 1.107 5.869 1.668 5.406 C 2.225 4.945 3.129 4.462 4.439 4.024 L 4.122 3.076 Z M 4.122 9.74 L 3.807 10.689 L 6.234 11.495 L 6.549 10.546 L 6.864 9.597 L 4.437 8.791 L 4.122 9.74 Z M 6.549 10.546 L 5.6 10.861 L 6.406 13.288 L 7.355 12.973 L 8.304 12.658 L 7.498 10.231 L 6.549 10.546 Z M 7.355 12.973 L 6.406 13.288 C 6.885 14.727 7.465 15.884 8.14 16.701 C 8.812 17.515 9.673 18.095 10.687 18.095 C 11.702 18.095 12.562 17.515 13.235 16.701 C 13.91 15.884 14.49 14.727 14.968 13.288 L 14.019 12.973 L 13.071 12.657 C 12.635 13.967 12.153 14.871 11.693 15.428 C 11.23 15.988 10.882 16.095 10.687 16.095 C 10.493 16.095 10.144 15.988 9.682 15.428 C 9.222 14.871 8.74 13.967 8.304 12.657 L 7.355 12.973 Z M 14.019 12.973 L 14.968 13.289 L 17.694 5.122 L 16.745 4.805 L 15.796 4.489 L 13.071 12.656 L 14.019 12.973 Z M 16.745 4.805 L 17.694 5.119 C 18.352 3.132 18.256 1.24 17.06 0.042 C 15.862 -1.157 13.97 -1.252 11.983 -0.59 L 12.299 0.359 L 12.615 1.308 C 14.295 0.748 15.232 1.042 15.645 1.455 C 16.058 1.869 16.352 2.811 15.796 4.491 L 16.745 4.805 Z",
    fill: "rgb(25,37,61)",
    fillRule: "nonzero"
  })));
}

// figma node: 1:1112 Dashboard
function Dashboard7(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1271,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1153,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 20,
      top: 0,
      display: "flex",
      flexDirection: "row",
      gap: 2,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 124,
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A \u062C\u0627\u0631\u06CC"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u0631\u0633\u0648\u0644\u0647 0 \u0627\u0632 0")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 78,
      height: 55,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 559,
      height: 638,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 506,
    viewBox: "0 0 559 506",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 84,
      width: 559,
      height: 506,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 519 0 C 541.091 0 559 17.909 559 40 L 559 449.684 C 559 477.22 531.826 496.522 505.826 487.453 L 26.826 320.376 C 10.762 314.773 0 299.621 0 282.607 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 229,
    viewBox: "0 0 559 229",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 409,
      width: 559,
      height: 229,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 56.57 C 0 28.962 27.305 9.655 53.334 18.858 L 546.757 193.32 C 554.094 195.914 559 202.852 559 210.635 C 559 220.778 550.778 229 540.635 229 L 40 229 C 17.909 229 0 211.091 0 189 L 0 56.57 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 529,
    height: 638,
    viewBox: "0 0 529 638",
    fill: "none",
    style: {
      position: "absolute",
      left: 30,
      top: 0,
      width: 529,
      height: 638
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 0 L 529 0 L 529 78.658 L 529 155.97 L 529 231.267 L 529 305.218 L 529 592.026 L 529 623.21 C 529 631.378 522.378 638 514.21 638 L 409.852 638 L 282.72 638 L 0 638 L 0 349.023 L 0 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 59,
      top: 1121,
      width: 281,
      height: 48
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 12,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 24,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 36,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 48,
      top: 0,
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0631\u0641\u062A\u0646 \u0628\u0647 \u0641\u0631\u0648\u0634\u06AF\u0627\u0647"))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 59,
      top: 799,
      width: 408,
      height: 58,
      fontFamily: "HastiFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 900,
      fontSize: 36,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0633\u0628\u062F \u0633\u0641\u0627\u0631\u0634\u0627\u062A \u0634\u0645\u0627 \u062E\u0627\u0644\u06CC \u0627\u0633\u062A"));
}

// figma node: 1:1177 Dashboard
function Dashboard8(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1355,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1153,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 697,
      top: 0,
      width: 277,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 20,
      top: 0,
      display: "flex",
      flexDirection: "row",
      gap: 2,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 177,
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 180,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A \u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u0631\u0633\u0648\u0644\u0647 0 \u0627\u0632 0")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 78,
      height: 55,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement(LinearShoppingEcommerceCartLarge, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 559,
      height: 638,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 506,
    viewBox: "0 0 559 506",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 84,
      width: 559,
      height: 506,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 519 0 C 541.091 0 559 17.909 559 40 L 559 449.684 C 559 477.22 531.826 496.522 505.826 487.453 L 26.826 320.376 C 10.762 314.773 0 299.621 0 282.607 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 229,
    viewBox: "0 0 559 229",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 409,
      width: 559,
      height: 229,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 56.57 C 0 28.962 27.305 9.655 53.334 18.858 L 546.757 193.32 C 554.094 195.914 559 202.852 559 210.635 C 559 220.778 550.778 229 540.635 229 L 40 229 C 17.909 229 0 211.091 0 189 L 0 56.57 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 529,
    height: 638,
    viewBox: "0 0 529 638",
    fill: "none",
    style: {
      position: "absolute",
      left: 30,
      top: 0,
      width: 529,
      height: 638
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 0 L 529 0 L 529 78.658 L 529 155.97 L 529 231.267 L 529 305.218 L 529 592.026 L 529 623.21 C 529 631.378 522.378 638 514.21 638 L 409.852 638 L 282.72 638 L 0 638 L 0 349.023 L 0 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 89,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, " \u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 59,
      top: 1121,
      width: 281,
      height: 48
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 12,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 24,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 36,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 48,
      top: 0,
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0631\u0641\u062A\u0646 \u0628\u0647 \u0641\u0631\u0648\u0634\u06AF\u0627\u0647"))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 59,
      top: 799,
      width: 414,
      height: 58,
      fontFamily: "HastiFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 900,
      fontSize: 36,
      textAlign: "right",
      lineHeight: "100%",
      letterSpacing: "-0.040em",
      color: "rgb(47,47,51)"
    }
  }, "\u0634\u0645\u0627 \u0647\u06CC\u0686 \u0645\u062D\u0635\u0648\u0644\u06CC \u0631\u0627 \u0630\u062E\u06CC\u0631\u0647 \u0646\u06A9\u0631\u062F\u0647 \u0627\u06CC\u062F"));
}

// figma node: 1:195 Hicon / Linear / Card 1
function HiconLinearCard1(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 24,
      height: 24,
      overflow: "hidden",
      position: "relative",
      color: "rgb(0,0,0)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 3,
      top: 4,
      width: 18,
      height: 16,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 18,
    height: 16,
    viewBox: "0 0 18 16",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 18,
      height: 16
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 8 C 0 7.236 0 6.577 0.011 6 C 0.041 4.461 0.149 3.508 0.545 2.73 C 1.024 1.789 1.789 1.024 2.73 0.545 C 3.8 0 5.2 0 8 0 L 10 0 C 12.8 0 14.2 0 15.27 0.545 C 16.211 1.024 16.976 1.789 17.455 2.73 C 17.851 3.508 17.959 4.461 17.989 6 C 18 6.577 18 7.236 18 8 C 18 10.8 18 12.2 17.455 13.27 C 16.976 14.211 16.211 14.976 15.27 15.455 C 14.2 16 12.8 16 10 16 L 8 16 C 5.2 16 3.8 16 2.73 15.455 C 1.789 14.976 1.024 14.211 0.545 13.27 C 0 12.2 0 10.8 0 8 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  }))));
}

// figma node: 1:198 Hicon / Linear / Dollar Circle
function HiconLinearDollarCircle(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 24,
      height: 24,
      overflow: "hidden",
      position: "relative",
      color: "rgb(0,0,0)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 2,
      top: 2,
      width: 20,
      height: 20,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 20,
    height: 20,
    viewBox: "0 0 20 20",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 20,
      height: 20
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 20 10 C 20 15.523 15.523 20 10 20 C 4.477 20 0 15.523 0 10 C 0 4.477 4.477 0 10 0 C 15.523 0 20 4.477 20 10 Z",
    fill: "currentColor",
    fillRule: "evenodd"
  }))));
}

// figma node: 1:189 Hicon / Linear / Refresh 1
function HiconLinearRefresh1(_p = {}) {
  return __figIcon(_p, 24, "rgb(0,0,0)", 1.5, ["M20.5 12a8.5 8.5 0 0 1-15.2 5.2","M3.5 12A8.5 8.5 0 0 1 18.7 6.8","M19 2.5v4.6h-4.6","M5 21.5v-4.6h4.6"]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 24,
      height: 24,
      overflow: "hidden",
      position: "relative",
      color: "rgb(0,0,0)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 2,
      top: 1,
      width: 20,
      height: 21.92,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 20,
      height: 21.92,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector")));
}

// figma node: 1:1242 Wallet
function Wallet(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1276,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1119,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 58.5 62.5 L 58.5 63 L 58.5 63.5 L 91 63.5 L 91 63 Z M 58.5 63 L 58.5 62.5 L 0 62.5 L 0 63 L 0 63.5 L 58.5 63.5 L 58.5 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      display: "flex",
      flexDirection: "row",
      gap: 2,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 124,
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 132,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 78,
      height: 55,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 42,
      top: 17,
      width: 20,
      height: 20,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 20,
    height: 20,
    viewBox: "0 0 20 20",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 20,
      height: 20,
      color: "rgb(255,255,255)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 2.395 19.263 L 2.787 18.624 L 2.787 18.624 L 2.395 19.263 Z M 0.745 17.612 L 1.385 17.221 L 1.385 17.221 L 0.745 17.612 Z M 19.264 17.612 L 18.624 17.221 L 18.624 17.221 L 19.264 17.612 Z M 17.614 19.263 L 17.222 18.624 L 17.222 18.624 L 17.614 19.263 Z M 17.614 3.737 L 17.222 4.376 L 17.222 4.376 L 17.614 3.737 Z M 19.264 5.388 L 18.624 5.779 L 18.624 5.779 L 19.264 5.388 Z M 2.395 3.737 L 2.787 4.376 L 2.787 4.376 L 2.395 3.737 Z M 0.745 5.388 L 1.385 5.779 L 1.385 5.779 L 0.745 5.388 Z M 16.478 0.546 L 17.02 0.027 L 17.02 0.027 L 16.478 0.546 Z M 1.852 0.891 L 2.305 1.489 L 2.305 1.489 L 1.852 0.891 Z M 0.863 1.923 L 1.478 2.351 L 0.863 1.923 Z M 17.001 3.13 L 17.751 3.13 L 17.751 3.127 L 17.001 3.13 Z M 16.365 11.614 C 16.779 11.614 17.115 11.278 17.115 10.864 C 17.115 10.449 16.779 10.114 16.365 10.114 L 16.365 10.864 L 16.365 11.614 Z M 13.639 10.114 C 13.225 10.114 12.889 10.449 12.889 10.864 C 12.889 11.278 13.225 11.614 13.639 11.614 L 13.639 10.864 L 13.639 10.114 Z M 8.505 3 L 8.505 3.75 L 11.504 3.75 L 11.504 3 L 11.504 2.25 L 8.505 2.25 L 8.505 3 Z M 11.504 20 L 11.504 19.25 L 8.505 19.25 L 8.505 20 L 8.505 20.75 L 11.504 20.75 L 11.504 20 Z M 8.505 20 L 8.505 19.25 C 6.855 19.25 5.669 19.249 4.745 19.161 C 3.832 19.074 3.251 18.908 2.787 18.624 L 2.395 19.263 L 2.003 19.903 C 2.742 20.355 3.58 20.557 4.603 20.655 C 5.615 20.751 6.884 20.75 8.505 20.75 L 8.505 20 Z M 0.009 11.5 L -0.741 11.5 C -0.741 13.122 -0.742 14.391 -0.646 15.404 C -0.548 16.427 -0.347 17.265 0.106 18.004 L 0.745 17.612 L 1.385 17.221 C 1.101 16.757 0.934 16.176 0.848 15.262 C 0.76 14.337 0.759 13.151 0.759 11.5 L 0.009 11.5 Z M 2.395 19.263 L 2.787 18.624 C 2.216 18.273 1.735 17.793 1.385 17.221 L 0.745 17.612 L 0.106 18.004 C 0.58 18.778 1.23 19.428 2.003 19.903 L 2.395 19.263 Z M 11.504 20 L 11.504 20.75 C 13.125 20.75 14.394 20.751 15.406 20.655 C 16.429 20.557 17.267 20.355 18.006 19.903 L 17.614 19.263 L 17.222 18.624 C 16.758 18.908 16.177 19.074 15.264 19.161 C 14.34 19.249 13.154 19.25 11.504 19.25 L 11.504 20 Z M 19.264 17.612 L 18.624 17.221 C 18.274 17.793 17.793 18.273 17.222 18.624 L 17.614 19.263 L 18.006 19.903 C 18.779 19.428 19.429 18.778 19.903 18.004 L 19.264 17.612 Z M 17.614 3.737 L 17.222 4.376 C 17.793 4.727 18.274 5.207 18.624 5.779 L 19.264 5.388 L 19.903 4.996 C 19.429 4.222 18.779 3.572 18.006 3.097 L 17.614 3.737 Z M 8.505 3 L 8.505 2.25 C 6.884 2.25 5.615 2.249 4.603 2.345 C 3.58 2.443 2.742 2.645 2.003 3.097 L 2.395 3.737 L 2.787 4.376 C 3.251 4.092 3.832 3.926 4.745 3.839 C 5.669 3.751 6.855 3.75 8.505 3.75 L 8.505 3 Z M 0.009 11.5 L 0.759 11.5 C 0.759 9.849 0.76 8.663 0.848 7.738 C 0.934 6.824 1.101 6.243 1.385 5.779 L 0.745 5.388 L 0.106 4.996 C -0.347 5.735 -0.548 6.573 -0.646 7.596 C -0.742 8.609 -0.741 9.878 -0.741 11.5 L 0.009 11.5 Z M 2.395 3.737 L 2.003 3.097 C 1.23 3.572 0.58 4.222 0.106 4.996 L 0.745 5.388 L 1.385 5.779 C 1.735 5.207 2.216 4.727 2.787 4.376 L 2.395 3.737 Z M 8.058 0 L 8.058 0.75 L 13.425 0.75 L 13.425 0 L 13.425 -0.75 L 8.058 -0.75 L 8.058 0 Z M 13.425 0 L 13.425 0.75 C 14.29 0.75 14.86 0.752 15.283 0.811 C 15.681 0.867 15.836 0.96 15.937 1.066 L 16.478 0.546 L 17.02 0.027 C 16.597 -0.414 16.068 -0.594 15.492 -0.674 C 14.94 -0.752 14.246 -0.75 13.425 -0.75 L 13.425 0 Z M 8.058 0 L 8.058 -0.75 C 6.399 -0.75 5.092 -0.751 4.054 -0.634 C 3.001 -0.515 2.137 -0.266 1.399 0.293 L 1.852 0.891 L 2.305 1.489 C 2.743 1.157 3.305 0.96 4.223 0.857 C 5.156 0.751 6.364 0.75 8.058 0.75 L 8.058 0 Z M 0.009 8.396 L 0.759 8.396 C 0.759 6.631 0.76 5.365 0.862 4.385 C 0.962 3.417 1.154 2.817 1.478 2.351 L 0.863 1.923 L 0.248 1.494 C -0.282 2.255 -0.517 3.142 -0.63 4.23 C -0.742 5.305 -0.741 6.663 -0.741 8.396 L 0.009 8.396 Z M 1.852 0.891 L 1.399 0.293 C 0.956 0.629 0.568 1.034 0.248 1.494 L 0.863 1.923 L 1.478 2.351 C 1.71 2.019 1.989 1.728 2.305 1.489 L 1.852 0.891 Z M 17.001 3.13 L 17.751 3.127 C 17.748 2.45 17.734 1.864 17.649 1.378 C 17.561 0.872 17.387 0.41 17.02 0.027 L 16.478 0.546 L 15.937 1.066 C 16.03 1.163 16.114 1.31 16.171 1.635 C 16.231 1.979 16.248 2.445 16.251 3.134 L 17.001 3.13 Z M 0.009 8.396 L -0.741 8.396 C -0.741 9.371 -0.761 10.264 -0.741 11.02 L 0.009 11 L 0.759 10.98 C 0.739 10.248 0.759 9.424 0.759 8.396 L 0.009 8.396 Z M 19.5 13.591 L 19.5 12.841 L 13.639 12.841 L 13.639 13.591 L 13.639 14.341 L 19.5 14.341 L 19.5 13.591 Z M 10.913 10.864 L 10.163 10.864 C 10.163 12.784 11.719 14.341 13.639 14.341 L 13.639 13.591 L 13.639 12.841 C 12.548 12.841 11.663 11.956 11.663 10.864 L 10.913 10.864 Z M 10.913 10.864 L 11.663 10.864 C 11.663 9.771 12.548 8.886 13.639 8.886 L 13.639 8.136 L 13.639 7.386 C 11.719 7.386 10.163 8.944 10.163 10.864 L 10.913 10.864 Z M 16.365 10.864 L 16.365 10.114 L 13.639 10.114 L 13.639 10.864 L 13.639 11.614 L 16.365 11.614 L 16.365 10.864 Z M 11.504 3 L 11.504 3.75 C 12.888 3.75 13.948 3.75 14.806 3.803 C 15.663 3.856 16.263 3.959 16.735 4.139 L 17.001 3.438 L 17.268 2.737 C 16.588 2.478 15.814 2.363 14.898 2.306 C 13.983 2.25 12.871 2.25 11.504 2.25 L 11.504 3 Z M 17.001 3.438 L 16.735 4.139 C 16.912 4.206 17.072 4.285 17.222 4.376 L 17.614 3.737 L 18.006 3.097 C 17.772 2.954 17.527 2.835 17.268 2.737 L 17.001 3.438 Z M 17.001 3.13 L 16.251 3.13 L 16.251 3.438 L 17.001 3.438 L 17.751 3.438 L 17.751 3.13 L 17.001 3.13 Z M 13.639 8.136 L 13.639 8.886 L 19.944 8.886 L 19.944 8.136 L 19.944 7.386 L 13.639 7.386 L 13.639 8.136 Z M 20 11.5 L 20.75 11.5 C 20.75 10.126 20.75 9.008 20.693 8.09 L 19.944 8.136 L 19.196 8.183 C 19.25 9.044 19.25 10.108 19.25 11.5 L 20 11.5 Z M 19.944 8.136 L 20.693 8.09 C 20.615 6.835 20.425 5.848 19.903 4.996 L 19.264 5.388 L 18.624 5.779 C 18.953 6.316 19.122 7.007 19.196 8.183 L 19.944 8.136 Z M 20 11.5 L 19.25 11.5 C 19.25 12.288 19.25 12.972 19.24 13.578 L 19.99 13.591 L 20.74 13.603 C 20.75 12.982 20.75 12.284 20.75 11.5 L 20 11.5 Z M 19.99 13.591 L 19.24 13.578 C 19.207 15.537 19.064 16.503 18.624 17.221 L 19.264 17.612 L 19.903 18.004 C 20.583 16.894 20.708 15.531 20.74 13.603 L 19.99 13.591 Z M 19.5 13.591 L 19.5 14.341 L 19.99 14.341 L 19.99 13.591 L 19.99 12.841 L 19.5 12.841 L 19.5 13.591 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 203,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement(LinearElectronicDevicesPrinter, {
    style: {
      position: "relative",
      width: 20,
      height: 20,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u067E\u0631\u06CC\u0646\u062A"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 12px 12px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 962,
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 180,
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-f42c575205896b59",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 744,
      height: 200,
      display: "flex",
      flexDirection: "column",
      justifyContent: "space-between",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0645\u0648\u062C\u0648\u062F\u06CC \u06A9\u06CC\u0641 \u067E\u0648\u0644"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(0,0,0)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u06F2,\u06F4\u06F5\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 294,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 144,
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "8px 8px 8px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 126,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0
    }
  }, "\u0628\u0631\u062F\u0627\u0634\u062A")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 144,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 500,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0627\u06CC\u0634 \u0645\u0648\u062C\u0648\u062F\u06CC"))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 112,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "flex-start",
      flexWrap: "wrap",
      gap: "20px 16px",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 315,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "16px 24px 16px 24px",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 45,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0628\u0631\u06AF\u0634\u062A \u0648\u062C\u0647"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 46,
      borderRadius: 40,
      backgroundColor: "rgb(231,234,237)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement(HiconLinearRefresh1, {
    style: {
      position: "relative",
      width: 24,
      height: 24,
      flexShrink: 0
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 25,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 72,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u06F3\u06F2\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 315,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "16px 24px 16px 24px",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 45,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u062C\u0645\u0648\u0639 \u067E\u0631\u062F\u0627\u062E\u062A"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 46,
      borderRadius: 40,
      backgroundColor: "rgb(231,234,237)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement(HiconLinearCard1, {
    style: {
      position: "relative",
      width: 24,
      height: 24,
      flexShrink: 0
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 25,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 72,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u06F1,\u06F3\u06F5\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 315,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "16px 24px 16px 24px",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 45,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u062C\u0645\u0648\u0639 \u0648\u0627\u0631\u06CC\u0632"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 46,
      borderRadius: 40,
      backgroundColor: "rgb(231,234,237)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement(HiconLinearDollarCircle, {
    style: {
      position: "relative",
      width: 24,
      height: 24,
      flexShrink: 0
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 25,
      display: "flex",
      flexDirection: "row",
      gap: 24,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 72,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u06F3,\u06F8\u06F0\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 356,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "32px 20px 32px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 956,
      display: "flex",
      flexDirection: "column",
      gap: 32,
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 37,
      display: "flex",
      flexDirection: "column",
      gap: 10,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 38,
      borderRadius: 12,
      backgroundColor: "rgb(244,245,249)",
      display: "flex",
      flexDirection: "row",
      gap: 506,
      padding: "13px 6px 13px 6px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 16,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 70,
      height: 29,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "10px 32px 10px 32px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(244,245,249)",
      flexShrink: 0
    }
  }, "\u0634\u0634 \u0645\u0627\u0647\u0647")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 70,
      height: 29,
      borderRadius: 60,
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "10px 32px 10px 32px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0647 \u0645\u0627\u0647\u0647")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 70,
      height: 29,
      borderRadius: 60,
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "10px 32px 10px 32px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0627\u0647\u0627\u0646\u0647"))))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u062A\u0627\u0631\u06CC\u062E\u0686\u0647 \u062A\u0631\u0627\u06A9\u0646\u0634\u200C\u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 16,
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 54,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0648\u0636\u0639\u06CC\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 60,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0646\u0648\u0639 \u062A\u0631\u0627\u06A9\u0646\u0634"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 82,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0628\u0644\u063A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 72,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u062A\u0627\u0631\u06CC\u062E"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 136,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0634\u0631\u062D \u062A\u0631\u0627\u06A9\u0646\u0634")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 16,
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 16,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    height: 1,
    viewBox: "0 -0.500 956 1",
    fill: "none",
    style: {
      position: "relative",
      transform: "matrix(-1,0,0,-1,0,0)",
      height: 1,
      flexShrink: 0,
      alignSelf: "stretch",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 -0.5 L 0 0 L 956 0 L 956 -0.5 L 956 -1 L 0 -1 L 0 -0.5 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 32,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "5px 12px 5px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0645\u0648\u0641\u0642")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 60,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0
    }
  }, "\u0648\u0627\u0631\u06CC\u0632"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 82,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F1,\u06F0\u06F0\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 72,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F1\u06F4\u06F0\u06F5/\u06F0\u06F7/\u06F1\u06F2"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 136,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0627\u06CC\u0634 \u0645\u0648\u062C\u0648\u062F\u06CC \u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 32,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "5px 12px 5px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0645\u0648\u0641\u0642")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 60,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0
    }
  }, "\u067E\u0631\u062F\u0627\u062E\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 82,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F4\u06F5\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 72,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F1\u06F4\u06F0\u06F5/\u06F0\u06F7/\u06F1\u06F0"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 136,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u067E\u0631\u062F\u0627\u062E\u062A \u0633\u0641\u0627\u0631\u0634")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 32,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "5px 12px 5px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0645\u0648\u0641\u0642")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 60,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0
    }
  }, "\u0628\u0631\u06AF\u0634\u062A \u0648\u062C\u0647"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 82,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F3\u06F2\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 72,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F1\u06F4\u06F0\u06F5/\u06F0\u06F7/\u06F0\u06F8"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 136,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0628\u0627\u0632\u06AF\u0634\u062A \u0648\u062C\u0647 \u0633\u0641\u0627\u0631\u0634")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 32,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "5px 12px 5px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0645\u0648\u0641\u0642")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 60,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(241,23,99)",
      flexShrink: 0
    }
  }, "\u067E\u0631\u062F\u0627\u062E\u062A"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 82,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06F4\u06F5\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 72,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, " \u06F1\u06F4\u06F0\u06F5/\u06F0\u06F7/09"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 136,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u062D\u0627\u0644\u062A \u062E\u0627\u0644\u06CC \u06A9\u06CC\u0641 \u067E\u0648\u0644")))))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:1398 Wallet
function Wallet2(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1276,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1119,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 837,
      top: 12,
      width: 137,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0627\u0641\u0632\u0627\u06CC\u0634 \u0645\u0648\u062C\u0648\u062F\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 539,
      borderRadius: 40,
      background: "linear-gradient(117.869deg, rgb(239,240,244) 4.15%, rgb(255,255,255) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(244,245,249), 0px 0px 80px 0px rgba(0,0,0,0.08)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 756,
      display: "flex",
      flexDirection: "column",
      gap: 48,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "مبلغ ", "(تومان)"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u062B\u0644\u0627 50.000.000 \u062A\u0648\u0645\u0627\u0646"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0631\u0641\u062A\u0646 \u0628\u0647 \u062F\u0631\u06AF\u0627\u0647"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:1449 Wallet
function Wallet3(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1276,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1119,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 908,
      top: 12,
      width: 66,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u0628\u0631\u062F\u0627\u0634\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 539,
      borderRadius: 40,
      background: "linear-gradient(117.869deg, rgb(239,240,244) 4.15%, rgb(255,255,255) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(244,245,249), 0px 0px 80px 0px rgba(0,0,0,0.08)",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "20px 20px 20px 20px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 10,
      padding: "20px 0px 20px 0px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u0648\u062C\u0648\u062F\u06CC \u06A9\u06CC\u0641 \u067E\u0648\u0644"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(0,0,0)",
      flexShrink: 0
    }
  }, "\u06F2,\u06F4\u06F5\u06F0,\u06F0\u06F0\u06F0 \u062A\u0648\u0645\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 756,
      display: "flex",
      flexDirection: "column",
      gap: 48,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "مبلغ برداشت ", "(تومان)"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u062B\u0644\u0627 50.000.000 \u062A\u0648\u0645\u0627\u0646")))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 368,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(66,66,66)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0634\u0645\u0627\u0631\u0647 \u0634\u0628\u0627"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 56,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 0.500px rgba(179,179,181,0.4), 0 0 0 0.500px rgba(179,179,181,0.4)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "14px 16px 14px 16px",
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      opacity: 0.35,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 12,
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0645\u062B\u0644\u0627 IR 602521500000055588900001"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0628\u0631\u062F\u0627\u0634\u062A"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:224 Linear / Like / Heart 
function LinearLikeHeart(_p = {}) {
  return __figIcon(_p, 20, "rgb(255,255,255)", 1.25, ["M10 17.083S1.667 12.6 1.667 7.25C1.667 4.8 3.55 2.737 5.9 2.737c1.75 0 3.25 1 4.1 2.45.85-1.45 2.35-2.45 4.1-2.45 2.35 0 4.233 2.063 4.233 4.513 0 5.35-8.333 9.833-8.333 9.833z"]);
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 20,
      height: 20,
      overflow: "hidden",
      borderRadius: 5,
      position: "relative",
      color: "rgb(255,255,255)",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1.667,
      top: 2.737,
      width: 16.667,
      height: 14.346,
      border: "1px dashed currentColor",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      overflow: "hidden",
      fontSize: 10,
      opacity: 0.45
    }
  }, "Vector"));
}

// figma node: 1:1508 Wishlist
function Wishlist(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1421,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1209,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 987,
    height: 63,
    viewBox: "0 0 987 63",
    fill: "none",
    style: {
      position: "absolute",
      left: -1,
      top: 0,
      width: 987,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 981.142 57.142 L 980.789 56.789 L 981.142 57.142 Z M 987 0 L 986.5 0 L 986.5 23 L 987 23 L 987.5 23 L 987.5 0 L 987 0 Z M 947 63 L 947 62.5 L 104.268 62.5 L 104.268 63 L 104.268 63.5 L 947 63.5 L 947 63 Z M 104.268 63 L 104.268 62.5 L 48.584 62.5 L 48.584 63 L 48.584 63.5 L 104.268 63.5 L 104.268 63 Z M 48.584 63 L 48.584 62.5 L 0 62.5 L 0 63 L 0 63.5 L 48.584 63.5 L 48.584 63 Z M 987 23 L 986.5 23 C 986.5 32.442 986.499 39.474 985.772 44.879 C 985.047 50.27 983.609 53.968 980.789 56.789 L 981.142 57.142 L 981.496 57.496 C 984.533 54.458 986.024 50.514 986.763 45.012 C 987.501 39.525 987.5 32.414 987.5 23 L 987 23 Z M 947 63 L 947 63.5 C 956.414 63.5 963.525 63.501 969.012 62.763 C 974.514 62.024 978.458 60.533 981.496 57.496 L 981.142 57.142 L 980.789 56.789 C 977.968 59.609 974.27 61.047 968.879 61.772 C 963.474 62.499 956.442 62.5 947 62.5 L 947 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 1147,
      top: 0,
      width: 141,
      height: 55
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 896,
      top: 12,
      width: 78,
      height: 31,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 63,
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 985.500,
    height: 63,
    viewBox: "0 0 985.500 63",
    fill: "none",
    style: {
      position: "absolute",
      left: 0.5,
      top: 0,
      width: 985.5,
      height: 63,
      borderRadius: 20,
      color: "rgb(230,233,243)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 979.642 57.142 L 979.289 56.789 L 979.642 57.142 Z M 985.5 0 L 985 0 L 985 23 L 985.5 23 L 986 23 L 986 0 L 985.5 0 Z M 945.5 63 L 945.5 62.5 L 91 62.5 L 91 63 L 91 63.5 L 945.5 63.5 L 945.5 63 Z M 91 63 L 91 62.5 L 67 62.5 L 67 63 L 67 63.5 L 91 63.5 L 91 63 Z M 67 63 L 67 62.5 L 0 62.5 L 0 63 L 0 63.5 L 67 63.5 L 67 63 Z M 985.5 23 L 985 23 C 985 32.442 984.999 39.474 984.272 44.879 C 983.547 50.27 982.109 53.968 979.289 56.789 L 979.642 57.142 L 979.996 57.496 C 983.033 54.458 984.524 50.514 985.263 45.012 C 986.001 39.525 986 32.414 986 23 L 985.5 23 Z M 945.5 63 L 945.5 63.5 C 954.914 63.5 962.025 63.501 967.512 62.763 C 973.014 62.024 976.958 60.533 979.996 57.496 L 979.642 57.142 L 979.289 56.789 C 976.468 59.609 972.77 61.047 967.379 61.772 C 961.974 62.499 954.942 62.5 945.5 62.5 L 945.5 63 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 750,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      height: 55
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 224,
      display: "flex",
      flexDirection: "row",
      gap: 2,
      justifyContent: "flex-end",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 124,
      display: "flex",
      flexDirection: "column",
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 132,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 20,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 78,
      height: 55,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 55,
    height: 78,
    viewBox: "0 0 55 78",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 55,
      height: 78,
      borderRadius: 16,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 49.407 C 0 44.382 2.36 39.65 6.374 36.627 L 29.374 19.302 C 39.917 11.361 55 18.883 55 32.083 L 55 62 C 55 70.837 47.837 78 39 78 L 16 78 C 7.163 78 0 70.837 0 62 L 0 49.407 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 41,
      top: 20,
      width: 20,
      height: 20
    }
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-a3413780eb2b49c8",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-38178af60c34ce30",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-ff69e331165e3819",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "ریمل پیپا مدل Legendary Lashes 503\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-2a5963fd2ec46d8c",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648\u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u062A\u06CC\u0633\u062A\u0648 \u0645\u062F\u0644 Viola \u062D\u062C\u0645 120 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 22,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 16,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-5d69704775688cf8",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch",
      whiteSpace: "pre-wrap"
    }
  }, "فلوئید ضد آفتاب رنگی آردن با SPF 50 حجم 75 میلی لیتر\n")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-75c6b8a5990fac57",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u062F\u0648 \u067E\u0631\u0641\u06CC\u0648\u0645 \u0632\u0646\u0627\u0646\u0647 \u067E\u06CC\u0646\u06A9 \u0648\u06CC\u0698\u0648\u0627\u0644 \u0645\u062F\u0644 Poison Girl \u062D\u062C\u0645 100 \u0645\u06CC\u0644\u06CC \u0644\u06CC\u062A\u0631")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 235,
      height: 375,
      borderRadius: 24,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 2px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "12px 12px 16px 12px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 208,
      display: "flex",
      flexDirection: "column",
      gap: 24,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 200,
      borderRadius: 24,
      backgroundColor: "rgb(248,248,248)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "20px 78px 20px 78px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "fig-asset-38178af60c34ce30",
    style: {
      position: "relative",
      width: 134,
      mixBlendMode: "darken",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "right",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u0627\u0633\u067E\u0631\u06CC \u0646\u0631\u0645 \u06A9\u0646\u0646\u062F\u0647 \u0645\u0648 \u06A9\u0648\u062F\u06A9 150 \u0645\u06CC\u0644 \u0628\u06CC\u0628\u06CC \u0641\u0631\u0633\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 157,
      borderRadius: 12,
      backgroundColor: "rgba(239,240,244,0.6)",
      display: "flex",
      flexDirection: "row",
      gap: 30,
      padding: "10px 8px 10px 8px",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(OutlineEssentionalUIAddCircle, {
    style: {
      position: "relative",
      width: 16,
      height: 16,
      flexShrink: 0
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 120,
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 4,
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0641\u0632\u0648\u062F\u0646 \u0628\u0647 \u0633\u0628\u062F \u062E\u0631\u06CC\u062F"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 40,
      height: 40,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 40,
    height: 40,
    viewBox: "0 0 40 40",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      transform: "matrix(0,1,1,0,0,0)",
      transformOrigin: "0 0",
      width: 40,
      height: 40,
      borderRadius: 12,
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 28 0 C 34.627 0 40 5.373 40 12 L 40 28 C 40 34.627 34.627 40 28 40 L 12 40 C 5.373 40 0 34.627 0 28 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement(LinearLikeHeart, {
    style: {
      position: "absolute",
      left: 10,
      top: 10,
      width: 20,
      height: 20
    }
  }))))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "row",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 5px 7px 6px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "58")), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 15.334,
      fontFamily: "IRANSansXFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      lineHeight: 1,
      color: "rgb(230,233,243)",
      flexShrink: 0
    }
  }, "..."), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "3")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(255,255,255)",
      boxShadow: "inset 0 0 0 1px rgb(230,233,243)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 400,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "2")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 32,
      height: 32,
      borderRadius: 8,
      backgroundColor: "rgb(241,23,99)",
      display: "flex",
      flexDirection: "row",
      gap: 10,
      padding: "9px 10px 7px 11px",
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 700,
      fontSize: 16,
      whiteSpace: "nowrap",
      lineHeight: 1,
      color: "rgb(255,255,255)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "1"))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))));
}

// figma node: 1:1820 Wishlist
function Wishlist2(_p = {}) {
  const props = _p;
  return /*#__PURE__*/React.createElement("div", {
    className: props.className,
    style: {
      width: 1440,
      height: 1421,
      overflow: "hidden",
      backgroundColor: "rgb(244,245,249)",
      position: "relative",
      ...props.style
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 58,
      top: 16,
      width: 1322,
      height: 1209,
      display: "flex",
      flexDirection: "column",
      gap: 60,
      alignItems: "flex-end",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 50,
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "row",
      gap: 20,
      justifyContent: "flex-end",
      alignItems: "flex-start",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 986,
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 20,
      justifyContent: "center",
      alignItems: "flex-end",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 559,
      height: 638,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 506,
    viewBox: "0 0 559 506",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 84,
      width: 559,
      height: 506,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 519 0 C 541.091 0 559 17.909 559 40 L 559 449.684 C 559 477.22 531.826 496.522 505.826 487.453 L 26.826 320.376 C 10.762 314.773 0 299.621 0 282.607 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 559,
    height: 229,
    viewBox: "0 0 559 229",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 409,
      width: 559,
      height: 229,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 56.57 C 0 28.962 27.305 9.655 53.334 18.858 L 546.757 193.32 C 554.094 195.914 559 202.852 559 210.635 C 559 220.778 550.778 229 540.635 229 L 40 229 C 17.909 229 0 211.091 0 189 L 0 56.57 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 529,
    height: 638,
    viewBox: "0 0 529 638",
    fill: "none",
    style: {
      position: "absolute",
      left: 30,
      top: 0,
      width: 529,
      height: 638
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 0 L 529 0 L 529 78.658 L 529 155.97 L 529 231.267 L 529 305.218 L 529 592.026 L 529 623.21 C 529 631.378 522.378 638 514.21 638 L 409.852 638 L 282.72 638 L 0 638 L 0 349.023 L 0 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 314,
      height: 595,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 308,
    height: 404,
    viewBox: "0 0 308 404",
    fill: "none",
    style: {
      position: "absolute",
      left: 3,
      top: 191,
      width: 308,
      height: 404,
      borderRadius: 40,
      filter: "drop-shadow(0px 0px 80px rgba(0,0,0,0.08))",
      color: "rgb(244,245,249)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 158.5 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 L 268 0 C 290.091 0 308 17.909 308 40 L 308 343.714 L 308 364 C 308 386.091 290.091 404 268 404 L 40 404 C 17.909 404 0 386.091 0 364 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 308 343.714 L 307 343.714 L 307 364 L 308 364 L 309 364 L 309 343.714 L 308 343.714 Z M 268 404 L 268 403 L 40 403 L 40 404 L 40 405 L 268 405 L 268 404 Z M 0 364 L 1 364 L 1 40 L 0 40 L -1 40 L -1 364 L 0 364 Z M 308 40 L 307 40 L 307 343.714 L 308 343.714 L 309 343.714 L 309 40 L 308 40 Z M 40 0 L 40 1 L 105.473 1 L 105.473 0 L 105.473 -1 L 40 -1 L 40 0 Z M 211.355 0 L 211.355 1 L 268 1 L 268 0 L 268 -1 L 211.355 -1 L 211.355 0 Z M 128.973 23.5 L 128.973 24.5 L 158.5 24.5 L 158.5 23.5 L 158.5 22.5 L 128.973 22.5 L 128.973 23.5 Z M 158.5 23.5 L 158.5 24.5 L 187.855 24.5 L 187.855 23.5 L 187.855 22.5 L 158.5 22.5 L 158.5 23.5 Z M 195.688 15.667 L 194.688 15.667 C 194.688 19.441 191.629 22.5 187.855 22.5 L 187.855 23.5 L 187.855 24.5 C 192.733 24.5 196.688 20.545 196.688 15.667 L 195.688 15.667 Z M 121.14 15.667 L 120.14 15.667 C 120.14 20.545 124.095 24.5 128.973 24.5 L 128.973 23.5 L 128.973 22.5 C 125.2 22.5 122.14 19.441 122.14 15.667 L 121.14 15.667 Z M 211.355 0 L 211.355 -1 C 202.15 -1 194.688 6.462 194.688 15.667 L 195.688 15.667 L 196.688 15.667 C 196.688 7.566 203.254 1 211.355 1 L 211.355 0 Z M 308 40 L 309 40 C 309 17.356 290.644 -1 268 -1 L 268 0 L 268 1 C 289.539 1 307 18.461 307 40 L 308 40 Z M 40 404 L 40 403 C 18.461 403 1 385.539 1 364 L 0 364 L -1 364 C -1 386.644 17.356 405 40 405 L 40 404 Z M 308 364 L 307 364 C 307 385.539 289.539 403 268 403 L 268 404 L 268 405 C 290.644 405 309 386.644 309 364 L 308 364 Z M 105.473 0 L 105.473 1 C 113.574 1 120.14 7.566 120.14 15.667 L 121.14 15.667 L 122.14 15.667 C 122.14 6.462 114.678 -1 105.473 -1 L 105.473 0 Z M 0 40 L 1 40 C 1 18.461 18.461 1 40 1 L 40 0 L 40 -1 C 17.356 -1 -1 17.356 -1 40 L 0 40 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 126,
      top: 98,
      width: 70,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 59.733,
      height: 56,
      overflow: "hidden",
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 59.733,
      height: 56,
      borderRadius: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 60,
      height: 56,
      opacity: 0.1,
      borderRadius: 12,
      backgroundColor: "rgb(241,23,99)"
    }
  }), /*#__PURE__*/React.createElement(COCOLineMenu, {
    style: {
      position: "absolute",
      left: 9.867,
      top: 8,
      width: 40,
      height: 40
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "flex",
      flexDirection: "column",
      gap: 4,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "\u067E\u06CC\u0634\u062E\u0648\u0627\u0646"), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 14,
      textAlign: "center",
      whiteSpace: "nowrap",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, "Dashboard"))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 45,
      top: 247,
      width: 233,
      display: "flex",
      flexDirection: "column",
      gap: 8,
      justifyContent: "center",
      alignItems: "center",
      flexWrap: "nowrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 108,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0627\u0637\u0644\u0627\u0639\u0627\u062A \u062D\u0633\u0627\u0628 \u06A9\u0627\u0631\u0628\u0631\u06CC")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0633\u0641\u0627\u0631\u0634\u0627\u062A")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u0630\u062E\u06CC\u0631\u0647 \u0634\u062F\u0647 \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 74,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(47,47,51)",
      flexShrink: 0
    }
  }, "\u06A9\u06CC\u0641 \u067E\u0648\u0644")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0639\u0644\u0627\u0642\u0645\u0646\u062F\u06CC \u0647\u0627")), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 48,
      borderRadius: 12,
      boxShadow: "inset 0 0 0 1px rgb(239,240,244)",
      display: "flex",
      flexDirection: "row",
      padding: "12px 16px 12px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      flexShrink: 0,
      alignSelf: "stretch"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 41,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "right",
      lineHeight: "100%",
      color: "rgb(227,19,54)",
      flexShrink: 0
    }
  }, "\u062E\u0631\u0648\u062C"))), /*#__PURE__*/React.createElement("svg", {
    width: 314,
    height: 171,
    viewBox: "0 0 314 171",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 314,
      height: 171,
      borderRadius: 40
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z",
    fill: "url(#elanoBlobGrad)",
    fillRule: "nonzero"
  })))))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 59,
      top: 951,
      width: 281,
      height: 48
    }
  }, /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 0,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 12,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 24,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("svg", {
    width: 233,
    height: 48,
    viewBox: "0 0 233 48",
    fill: "none",
    style: {
      position: "absolute",
      left: 36,
      top: 0,
      width: 233,
      height: 48,
      opacity: 0.1,
      borderRadius: 12,
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box",
      filter: "drop-shadow(0px 20px 36px rgba(253,86,144,0.3))",
      color: "rgb(241,23,99)"
    }
  }, /*#__PURE__*/React.createElement("path", {
    d: "M 0 12 C 0 5.373 5.373 0 12 0 L 221 0 C 227.627 0 233 5.373 233 12 L 233 36 C 233 42.627 227.627 48 221 48 L 12 48 C 5.373 48 0 42.627 0 36 L 0 12 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  }), /*#__PURE__*/React.createElement("path", {
    d: "M 12 0 L 12 1 L 221 1 L 221 0 L 221 -1 L 12 -1 L 12 0 Z M 233 12 L 232 12 L 232 36 L 233 36 L 234 36 L 234 12 L 233 12 Z M 221 48 L 221 47 L 12 47 L 12 48 L 12 49 L 221 49 L 221 48 Z M 0 36 L 1 36 L 1 12 L 0 12 L -1 12 L -1 36 L 0 36 Z M 12 48 L 12 47 C 5.925 47 1 42.075 1 36 L 0 36 L -1 36 C -1 43.18 4.82 49 12 49 L 12 48 Z M 233 36 L 232 36 C 232 42.075 227.075 47 221 47 L 221 48 L 221 49 C 228.18 49 234 43.18 234 36 L 233 36 Z M 221 0 L 221 1 C 227.075 1 232 5.925 232 12 L 233 12 L 234 12 C 234 4.82 228.18 -1 221 -1 L 221 0 Z M 12 0 L 12 -1 C 4.82 -1 -1 4.82 -1 12 L 0 12 L 1 12 C 1 5.925 5.925 1 12 1 L 12 0 Z",
    fill: "currentColor",
    fillRule: "nonzero"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 48,
      top: 0,
      width: 233,
      height: 48,
      borderRadius: 12,
      background: "linear-gradient(90deg, rgb(241,23,99) 0.00%, rgb(251,57,124) 100.00%)",
      boxShadow: "inset 0 0 0 1px rgb(241,23,99), 0px 20px 36px 0px rgba(253,86,144,0.3)",
      display: "flex",
      flexDirection: "row",
      padding: "8px 16px 8px 16px",
      justifyContent: "space-between",
      alignItems: "center",
      flexWrap: "nowrap",
      boxSizing: "border-box"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      width: 12,
      height: 12,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement(VuesaxLinearArrowLeft, {
    style: {
      transform: "scale(0.500, 0.500)",
      transformOrigin: "0 0"
    }
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      width: 95,
      fontFamily: "\"Yekan Bakh FaNum\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 600,
      fontSize: 12,
      textAlign: "center",
      lineHeight: "100%",
      color: "rgb(255,255,255)",
      flexShrink: 0
    }
  }, "\u0631\u0641\u062A\u0646 \u0628\u0647 \u0641\u0631\u0648\u0634\u06AF\u0627\u0647"))), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 59,
      top: 629,
      width: 414,
      height: 116,
      fontFamily: "HastiFaNum, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif",
      fontWeight: 900,
      fontSize: 36,
      textAlign: "center",
      lineHeight: "100%",
      letterSpacing: "-0.040em",
      color: "rgb(47,47,51)"
    }
  }, "\u0634\u0645\u0627 \u0647\u06CC\u0686 \u0645\u062D\u0635\u0648\u0644\u06CC \u0631\u0627 \u0628\u0647 \u0639\u0644\u0627\u0642\u0647 \u0645\u0646\u062F\u06CC \u062E\u0648\u062F \u0627\u0636\u0627\u0641\u0647  \u0646\u06A9\u0631\u062F\u0647 \u0627\u06CC\u062F"));
}

// Globals for scripts loaded after this file.
window.COCOLineMenu = COCOLineMenu;
window.COCOLineSaved = COCOLineSaved;
window.LinearShoppingEcommerceCartCheck = LinearShoppingEcommerceCartCheck;
window.LinearShoppingEcommerceCartCross = LinearShoppingEcommerceCartCross;
window.LinearShoppingEcommerceCartLarge = LinearShoppingEcommerceCartLarge;
window.OutlineEssentionalUIAddCircle = OutlineEssentionalUIAddCircle;
window.VuesaxLinearArrowLeft = VuesaxLinearArrowLeft;
window.Dashboard = Dashboard;
window.Dashboard2 = Dashboard2;
window.LinearElectronicDevicesPrinter = LinearElectronicDevicesPrinter;
window.Dashboard3 = Dashboard3;
window.Dashboard4 = Dashboard4;
window.Dashboard5 = Dashboard5;
window.Dashboard6 = Dashboard6;
window.Dashboard7 = Dashboard7;
window.Dashboard8 = Dashboard8;
window.HiconLinearCard1 = HiconLinearCard1;
window.HiconLinearDollarCircle = HiconLinearDollarCircle;
window.HiconLinearRefresh1 = HiconLinearRefresh1;
window.Wallet = Wallet;
window.Wallet2 = Wallet2;
window.Wallet3 = Wallet3;
window.LinearLikeHeart = LinearLikeHeart;
window.Wishlist = Wishlist;
window.Wishlist2 = Wishlist2;