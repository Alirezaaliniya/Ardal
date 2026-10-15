import { LinearSearchMagnifer } from './LinearSearchMagnifer.jsx';
import { OutlineEssentionalUISort } from './OutlineEssentionalUISort.jsx';

// figma node: 7:698 Header Mobile
export function HeaderMobile(_p = {}) {
  const props = _p;
  return (
    <div className={props.className} style={{
      width: 358,
      height: 107,
      position: "relative",
      ...props.style,
    }}>
      <div style={{
        position: "absolute",
        left: 0,
        top: 0,
        width: 358,
        height: 107,
        borderRadius: 24,
        backgroundColor: "rgb(255,255,255)",
        boxShadow: "inset 0 0 0 1px rgb(255,255,255)",
      }} />
      <div style={{
        position: "absolute",
        left: 121,
        top: 34,
        width: 116,
        height: 38,
        overflow: "hidden",
      }}>
        <div style={{
          position: "absolute",
          left: 0.002,
          top: 0,
          width: 115.997,
          height: 38,
          overflow: "hidden",
        }}>
          <svg width={16.193} height={22.027} viewBox="0 0 16.193 22.027" fill="none" style={{
            position: "absolute",
            left: 0,
            top: 0,
            width: 16.193,
            height: 22.027,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 2.573 9.842 L 2.573 2.536 L 16.193 2.536 L 16.193 0 L 0 0 L 0 22.027 L 16.193 22.027 L 16.193 19.491 L 2.573 19.491 L 2.573 12.378 L 13.129 12.378 L 13.129 9.842 L 2.573 9.842 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={14.168} height={22.027} viewBox="0 0 14.168 22.027" fill="none" style={{
            position: "absolute",
            left: 23.129,
            top: 0,
            width: 14.168,
            height: 22.027,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 0 L 0 22.027 L 14.168 22.027 L 14.168 19.491 L 2.573 19.491 L 2.573 0 L 0 0 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={23.528} height={21.883} viewBox="0 0 23.528 21.883" fill="none" style={{
            position: "absolute",
            left: 41.042,
            top: 0.144,
            width: 23.528,
            height: 21.883,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 21.883 L 2.912 21.883 L 11.764 5.418 L 20.616 21.883 L 23.528 21.883 L 11.764 0 L 0 21.883 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={18.136} height={22.027} viewBox="0 0 18.136 22.027" fill="none" style={{
            position: "absolute",
            left: 69.55,
            top: 0,
            width: 18.136,
            height: 22.027,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 22.027 L 2.573 22.027 L 2.573 6.248 L 18.136 22.027 L 18.136 0 L 15.563 0 L 15.563 15.779 L 0 0 L 0 22.027 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={22.350} height={22.027} viewBox="0 0 22.350 22.027" fill="none" style={{
            position: "absolute",
            left: 93.646,
            top: 0,
            width: 22.35,
            height: 22.027,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 11.013 C 0 17.096 5.004 22.027 11.176 22.027 C 17.347 22.027 22.35 17.096 22.35 11.013 C 22.35 4.931 17.347 0 11.176 0 C 5.004 0 0 4.931 0 11.013 Z M 11.176 19.491 C 6.424 19.491 2.573 15.695 2.573 11.013 C 2.573 6.331 6.424 2.536 11.176 2.536 C 15.926 2.536 19.777 6.331 19.777 11.013 C 19.777 15.695 15.926 19.491 11.176 19.491 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={49.502} height={6.671} viewBox="0 0 49.502 6.671" fill="none" style={{
            position: "absolute",
            left: 33.247,
            top: 31.329,
            width: 49.502,
            height: 6.671,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 4.489 L 0.814 4.417 C 0.852 4.741 0.942 5.007 1.084 5.216 C 1.226 5.424 1.445 5.591 1.742 5.721 C 2.039 5.848 2.375 5.913 2.746 5.913 C 3.077 5.913 3.369 5.865 3.622 5.767 C 3.875 5.669 4.063 5.536 4.186 5.366 C 4.309 5.197 4.372 5.011 4.372 4.813 C 4.372 4.613 4.312 4.437 4.193 4.285 C 4.074 4.135 3.878 4.008 3.604 3.904 C 3.429 3.839 3.04 3.734 2.438 3.591 C 1.835 3.448 1.414 3.314 1.173 3.188 C 0.862 3.025 0.63 2.824 0.477 2.585 C 0.323 2.347 0.247 2.078 0.247 1.782 C 0.247 1.455 0.341 1.149 0.529 0.867 C 0.717 0.583 0.989 0.368 1.351 0.221 C 1.711 0.074 2.111 0 2.551 0 C 3.036 0 3.465 0.077 3.835 0.231 C 4.206 0.384 4.49 0.612 4.691 0.912 C 4.89 1.211 4.996 1.55 5.012 1.929 L 4.18 1.992 C 4.136 1.585 3.985 1.277 3.727 1.07 C 3.469 0.86 3.089 0.758 2.586 0.758 C 2.061 0.758 1.678 0.852 1.439 1.041 C 1.2 1.231 1.08 1.459 1.08 1.726 C 1.08 1.957 1.164 2.149 1.334 2.299 C 1.501 2.448 1.936 2.602 2.64 2.758 C 3.344 2.916 3.828 3.053 4.09 3.17 C 4.47 3.343 4.751 3.561 4.933 3.826 C 5.114 4.09 5.204 4.396 5.204 4.741 C 5.204 5.086 5.105 5.407 4.906 5.711 C 4.705 6.014 4.421 6.25 4.047 6.418 C 3.674 6.586 3.254 6.671 2.786 6.671 C 2.195 6.671 1.699 6.584 1.3 6.415 C 0.899 6.245 0.587 5.99 0.36 5.647 C 0.131 5.307 0.013 4.92 0 4.489 Z M 14.552 6.563 L 14.552 0.108 L 15.421 0.108 L 15.421 2.767 L 18.806 2.767 L 18.806 0.108 L 19.675 0.108 L 19.675 6.563 L 18.806 6.563 L 18.806 3.524 L 15.421 3.524 L 15.421 6.563 L 14.552 6.563 Z M 28.987 3.421 C 28.987 2.351 29.281 1.513 29.864 0.908 C 30.448 0.302 31.201 0 32.125 0 C 32.73 0 33.275 0.143 33.761 0.427 C 34.246 0.71 34.616 1.107 34.87 1.616 C 35.125 2.124 35.253 2.702 35.253 3.346 C 35.253 3.999 35.119 4.585 34.851 5.101 C 34.583 5.617 34.203 6.008 33.712 6.272 C 33.219 6.538 32.688 6.671 32.12 6.671 C 31.502 6.671 30.953 6.524 30.467 6.231 C 29.981 5.938 29.612 5.538 29.363 5.03 C 29.113 4.523 28.987 3.988 28.987 3.421 Z M 29.883 3.436 C 29.883 4.213 30.096 4.824 30.52 5.271 C 30.943 5.717 31.475 5.94 32.116 5.94 C 32.768 5.94 33.303 5.715 33.725 5.264 C 34.147 4.813 34.356 4.174 34.356 3.344 C 34.356 2.82 34.266 2.361 34.087 1.971 C 33.907 1.58 33.643 1.277 33.297 1.062 C 32.949 0.847 32.561 0.739 32.129 0.739 C 31.517 0.739 30.989 0.947 30.547 1.361 C 30.105 1.775 29.883 2.467 29.883 3.436 Z M 44.499 6.563 L 44.499 0.108 L 46.971 0.108 C 47.406 0.108 47.738 0.13 47.968 0.17 C 48.29 0.222 48.56 0.323 48.778 0.47 C 48.996 0.619 49.171 0.827 49.304 1.092 C 49.436 1.358 49.502 1.651 49.502 1.971 C 49.502 2.518 49.326 2.982 48.97 3.361 C 48.616 3.741 47.975 3.93 47.047 3.93 L 45.368 3.93 L 45.368 6.563 L 44.499 6.563 Z M 45.368 3.173 L 47.06 3.173 C 47.621 3.173 48.018 3.071 48.253 2.865 C 48.488 2.659 48.606 2.371 48.606 1.996 C 48.606 1.728 48.537 1.497 48.398 1.305 C 48.26 1.111 48.077 0.984 47.851 0.922 C 47.705 0.885 47.434 0.866 47.043 0.866 L 45.368 0.866 L 45.368 3.173 Z"} fill="currentColor" fillRule="evenodd" />
          </svg>
          <svg width={29.485} height={0.692} viewBox="0 0 29.485 0.692" fill="none" style={{
            position: "absolute",
            left: 0,
            top: 34.284,
            width: 29.485,
            height: 0.692,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 0 L 29.485 0 L 29.485 0.692 L 0 0.692 L 0 0 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
          <svg width={29.485} height={0.692} viewBox="0 0 29.485 0.692" fill="none" style={{
            position: "absolute",
            left: 86.512,
            top: 34.284,
            width: 29.485,
            height: 0.692,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 0 L 29.485 0 L 29.485 0.692 L 0 0.692 L 0 0 Z"} fill="currentColor" fillRule="nonzero" />
          </svg>
        </div>
      </div>
      <div style={{
        position: "absolute",
        left: 12,
        top: 29,
        display: "flex",
        flexDirection: "row",
        gap: 2,
        alignItems: "center",
        flexWrap: "nowrap",
      }}>
        <div style={{
          position: "relative",
          width: 52,
          height: 48,
          boxShadow: "0px 12px 20px 0px rgb(239,240,244)",
          flexShrink: 0,
        }}>
          <svg width={54} height={48} viewBox="0 0 54 48" fill="none" style={{
            position: "absolute",
            left: 0,
            top: 0,
            width: 54,
            height: 48,
            borderRadius: 12,
            color: "rgb(0,0,0)",
          }}>
            <path d={"M 0 0 L 46.756 0 L 54 48 L 0 48 L 0 0 Z"} fill="currentColor" fillRule="evenodd" />
          </svg>
          <div style={{
              position: "absolute",
              left: 15,
              top: 13,
              width: 22,
              height: 22,
            }}>{props.icon1 ?? <LinearSearchMagnifer style={{ transform: "scale(0.917, 0.917)", transformOrigin: "0 0" }} />}</div>
        </div>
      </div>
      <div style={{
        position: "absolute",
        left: 290,
        top: 29,
        width: 52,
        height: 48,
        boxShadow: "0px 12px 20px 0px rgb(239,240,244)",
      }}>
        <svg width={54} height={48} viewBox="0 0 54 48" fill="none" style={{
          position: "absolute",
          left: 0,
          top: 0,
          transform: "matrix(-1,0,0,-1,52,48)",
          transformOrigin: "0 0",
          width: 54,
          height: 48,
          borderRadius: 12,
          color: "rgb(255,255,255)",
        }}>
          <path d={"M 0 0 L 45.692 0 L 54 48 L 0 47.759 L 0 23.879 L 0 0 Z"} fill="currentColor" fillRule="evenodd" />
        </svg>
        <div style={{
            position: "absolute",
            left: 14,
            top: 12,
            width: 24,
            height: 24,
          }}>{props.icon2 ?? <OutlineEssentionalUISort />}</div>
        <div style={{
          position: "absolute",
          left: 14,
          top: 12,
          width: 24,
          height: 24,
          display: "flex",
          flexDirection: "row",
          gap: 16,
          alignItems: "center",
          flexWrap: "nowrap",
        }} />
      </div>
    </div>
  );
}
export default HeaderMobile;
