import * as React from 'react';
export interface HeaderProps {
  className?: string;
  style?: React.CSSProperties;
  /** Text content; defaults to "شگفت انگیز". */
  text1?: string;
  /** Text content; defaults to "عطر و اسپری". */
  text2?: string;
  /** Text content; defaults to "مراقبت پوست و زیبایی". */
  text3?: string;
  /** Text content; defaults to "پیشنهاد ویژه". */
  text4?: string;
  /** Swappable nested instance; defaults to the design's. */
  icon1?: React.ReactNode;
  /** Swappable nested instance; defaults to the design's. */
  icon2?: React.ReactNode;
  /** Swappable nested instance; defaults to the design's. */
  icon3?: React.ReactNode;
  /** Swappable nested instance; defaults to the design's. */
  icon4?: React.ReactNode;
}
export declare const Header: React.FC<HeaderProps>;
export default Header;
