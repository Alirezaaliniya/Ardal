// figma node: 7:745 Linear / Essentional, UI / Mirror
export function LinearEssentionalUIMirror(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 16,
      height: 16,
      overflow: "hidden",
      position: "relative",
      color: "rgb(255,255,255)",
      ...props.style,
    }}>
      <div style={{
        position: "absolute",
        left: 2,
        top: 2,
        width: 12,
        height: 9.333,
        border: "1px dashed currentColor",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        overflow: "hidden",
        fontSize: 10,
        opacity: 0.45,
      }}>Vector</div>
      <svg width={13.333} height={3.333} viewBox="0 0 13.333 3.333" fill="none" style={{
        position: "absolute",
        left: 1.333,
        top: 11.333,
        width: 13.333,
        height: 3.333,
      }}>
        <path d={"M 10.909 3.333 L 2.424 3.333 M 10.909 3.333 C 12.248 3.333 13.333 2.248 13.333 0.909 C 13.333 0.407 12.926 0 12.424 0 L 9.886 0 C 9.532 0 9.193 0.14 8.943 0.391 L 8.391 0.943 C 8.14 1.193 7.801 1.333 7.448 1.333 L 5.886 1.333 C 5.532 1.333 5.193 1.193 4.943 0.943 L 4.391 0.391 C 4.14 0.14 3.801 0 3.448 0 L 0.909 0 C 0.407 0 0 0.407 0 0.909 C 0 2.248 1.085 3.333 2.424 3.333 Z"} fill="currentColor" fillRule="evenodd" />
      </svg>
    </div>
  );
}
export default LinearEssentionalUIMirror;
