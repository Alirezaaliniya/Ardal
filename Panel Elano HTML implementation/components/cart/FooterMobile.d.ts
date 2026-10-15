import * as React from 'react';
export interface FooterMobileProps {
  className?: string;
  style?: React.CSSProperties;
  /** Text content; defaults to "(021)-123456789". */
  text1?: string;
  /** Text content; defaults to "ساعات کاری". */
  text2?: string;
  /** Text content; defaults to "ما 7 روز هفته پاسخگوی شما هستیم". */
  text3?: string;
  /** Text content; defaults to "ارسال به تمام محدوده تهران". */
  text4?: string;
}
export declare const FooterMobile: React.FC<FooterMobileProps>;
export default FooterMobile;
