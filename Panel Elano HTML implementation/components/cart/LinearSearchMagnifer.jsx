// figma node: 7:690 Linear / Search / Magnifer
export function LinearSearchMagnifer(_p = {}) {
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
        left: 2,
        top: 2,
        width: 19,
        height: 19,
        borderRadius: "50%",
        boxShadow: "inset 0 0 0 0.750px rgb(47,47,51), 0 0 0 0.750px rgb(47,47,51)",
      }} />
      <div style={{
        position: "absolute",
        left: 18.5,
        top: 18.5,
        width: 3.5,
        height: 3.5,
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
export default LinearSearchMagnifer;
