import * as React from 'react';
export interface HeaderMobileProps {
  className?: string;
  style?: React.CSSProperties;
  /** Swappable nested instance; defaults to the design's. */
  icon1?: React.ReactNode;
  /** Swappable nested instance; defaults to the design's. */
  icon2?: React.ReactNode;
}
export declare const HeaderMobile: React.FC<HeaderMobileProps>;
export default HeaderMobile;
