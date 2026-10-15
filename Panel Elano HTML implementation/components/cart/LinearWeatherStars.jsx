// figma node: 7:756 Linear / Weather / Stars
export function LinearWeatherStars(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 24,
      height: 24,
      position: "relative",
      color: "rgb(47,47,51)",
      ...props.style,
    }}>
      <svg width={12} height={12} viewBox="0 0 12 12" fill="none" style={{
        position: "absolute",
        left: 3,
        top: 3,
        width: 12,
        height: 12,
      }}>
        <path d={"M 6.967 0.658 C 6.621 -0.219 5.379 -0.219 5.033 0.658 M 6.967 0.658 L 8.039 3.376 C 8.144 3.644 8.356 3.856 8.624 3.961 L 11.342 5.033 C 12.219 5.379 12.219 6.621 11.342 6.967 L 8.624 8.039 C 8.356 8.144 8.144 8.356 8.039 8.624 L 6.967 11.342 C 6.621 12.219 5.379 12.219 5.033 11.342 L 3.961 8.624 C 3.856 8.356 3.644 8.144 3.376 8.039 L 0.658 6.967 C -0.219 6.621 -0.219 5.379 0.658 5.033 L 3.376 3.961 C 3.644 3.856 3.856 3.644 3.961 3.376 L 5.033 0.658 Z"} fill="currentColor" fillRule="evenodd" />
      </svg>
      <svg width={8} height={8} viewBox="0 0 8 8" fill="none" style={{
        position: "absolute",
        left: 13,
        top: 13,
        width: 8,
        height: 8,
      }}>
        <path d={"M 4.512 0.348 C 4.328 -0.116 3.672 -0.116 3.488 0.348 M 4.512 0.348 L 5.312 2.378 C 5.368 2.52 5.48 2.632 5.622 2.688 L 7.652 3.488 C 8.116 3.672 8.116 4.328 7.652 4.512 L 5.622 5.312 C 5.48 5.368 5.368 5.48 5.312 5.622 L 4.512 7.652 C 4.328 8.116 3.672 8.116 3.488 7.652 L 2.688 5.622 C 2.632 5.48 2.52 5.368 2.378 5.312 L 0.348 4.512 C -0.116 4.328 -0.116 3.672 0.348 3.488 L 2.378 2.688 C 2.52 2.632 2.632 2.52 2.688 2.378 L 3.488 0.348 Z"} fill="currentColor" fillRule="evenodd" />
      </svg>
    </div>
  );
}
export default LinearWeatherStars;
