// figma node: 7:766 COCO/Line/Call - Calling
export function COCOLineCallCalling(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 20,
      height: 20,
      position: "relative",
      color: "rgb(255,255,255)",
      ...props.style,
    }}>
      <div style={{
        position: "absolute",
        left: 10.833,
        top: 2.5,
        width: 6.667,
        height: 6.667,
        border: "1px dashed currentColor",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        overflow: "hidden",
        fontSize: 10,
        opacity: 0.45,
      }}>Vector</div>
      <svg width={15} height={15} viewBox="0 0 15 15" fill="none" style={{
        position: "absolute",
        left: 2.5,
        top: 2.5,
        width: 15,
        height: 15,
      }}>
        <path d={"M 7.5 11.919 C 7.5 10.859 8.448 10 9.618 10 M 7.5 11.919 C 7.5 12.16 7.537 12.399 7.609 12.63 L 7.678 12.853 C 8.006 13.902 8.957 14.695 10.138 14.903 C 10.872 15.032 11.628 15.032 12.362 14.903 C 13.543 14.695 14.494 13.902 14.822 12.853 L 14.891 12.63 C 14.963 12.399 15 12.16 15 11.919 C 15 10.859 14.052 10 12.882 10 L 9.618 10 Z"} fill="currentColor" fillRule="evenodd" />
        <path d={"M 3.081 0 C 4.141 0 5 0.948 5 2.118 M 3.081 0 C 2.84 0 2.601 0.037 2.37 0.109 L 2.147 0.178 C 1.098 0.506 0.305 1.457 0.097 2.638 C -0.032 3.372 -0.032 4.128 0.097 4.862 C 0.305 6.043 1.098 6.994 2.147 7.322 L 2.37 7.391 C 2.601 7.463 2.84 7.5 3.081 7.5 C 4.141 7.5 5 6.552 5 5.382 L 5 2.118 Z"} fill="currentColor" fillRule="evenodd" />
      </svg>
    </div>
  );
}
export default COCOLineCallCalling;
