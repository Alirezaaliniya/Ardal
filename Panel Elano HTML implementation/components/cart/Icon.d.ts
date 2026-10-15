import * as React from 'react';
export type IconName =
  | "COCOBoldMinus"
  | "COCOBoldPlus"
  | "COCOLineCallCalling"
  | "COCOLineMenu"
  | "COCOLineSaved"
  | "HiconLinearCard1"
  | "HiconLinearDollarCircle"
  | "HiconLinearRecord"
  | "LinearEssentionalUIGift"
  | "LinearEssentionalUIMirror"
  | "LinearEssentionalUIPerfume"
  | "LinearMoneyTicketSale"
  | "LinearMoneyVerifiedCheck"
  | "LinearSettingsFineTuningWidget"
  | "LinearShoppingEcommerceBagSmile"
  | "LinearShoppingEcommerceCartCheck"
  | "LinearShoppingEcommerceCartCross"
  | "LinearShoppingEcommerceCartLarge"
  | "LinearUsersUser"
  | "LinearWeatherStars"
  | "OutlineEssentionalUIAddCircle"
  | "OutlineEssentionalUISort"
  | "OutlineEssentionalUITrashBin"
  | "OutlineShoppingEcommerceCartLarge"
  | "VuesaxLinearArrowLeft"
  | "VuesaxLinearBag2"
  | "VuesaxLinearHeart";
export interface IconProps extends React.SVGProps<SVGSVGElement> {
  name: IconName;
  size?: number | string;
}
export declare const Icon: React.FC<IconProps>;
export default Icon;
