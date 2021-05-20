import { collapseTextChangeRangesAcrossMultipleVersions } from "typescript";

interface columns {}
export interface Data {
  key?: number;
  id: number;
  title: string;
}

export interface State {
  visible: boolean;
  data: Array<Data>;
}
