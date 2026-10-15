// figma node: 7:759 Linear / Arrows / Alt Arrow Down
export function LinearArrowsAltArrowDown(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 24,
      height: 24,
      overflow: "hidden",
      position: "relative",
      color: "rgb(47,47,51)",
      ...props.style,
    }}>
      <div style={{
        position: "absolute",
        left: 5,
        top: 9,
        width: 14,
        height: 6,
        border: "1px dashed currentColor",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        overflow: "hidden",
        fontSize: 10,
        opacity: 0.45,
      }}>Vector</div>
    </div>
  );
}
export default LinearArrowsAltArrowDown;
