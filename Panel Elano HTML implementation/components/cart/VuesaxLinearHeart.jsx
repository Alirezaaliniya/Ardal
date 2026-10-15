// figma node: 1:57 vuesax/linear/heart
export function VuesaxLinearHeart(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 24,
      height: 24,
      position: "relative",
      ...props.style,
    }}>
      <div style={{
        position: "absolute",
        left: 0,
        top: 0,
        width: 24,
        height: 24,
        overflow: "hidden",
      }}>
        <div style={{
          position: "absolute",
          left: 0,
          top: 0,
          width: 24,
          height: 24,
          overflow: "hidden",
        }}>
          <svg width={20} height={17.800} viewBox="0 0 20 17.800" fill="none" style={{
            position: "absolute",
            left: 2,
            top: 3.1,
            width: 20,
            height: 17.8,
            color: "rgb(68,68,68)",
          }}>
            <path d={"M 10.62 17.71 C 10.28 17.83 9.72 17.83 9.38 17.71 C 6.48 16.72 0 12.59 0 5.59 C 0 2.5 2.49 0 5.56 0 C 7.38 0 8.99 0.88 10 2.24 C 11.01 0.88 12.63 0 14.44 0 C 17.51 0 20 2.5 20 5.59 C 20 12.59 13.52 16.72 10.62 17.71 Z"} fill="currentColor" fillRule="evenodd" />
          </svg>
          <div style={{
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
            fontSize: 10,
          }}>Vector</div>
        </div>
      </div>
    </div>
  );
}
export default VuesaxLinearHeart;
