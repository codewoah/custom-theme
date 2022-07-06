import {disableBodyScroll,clearAllBodyScrollLocks} from "body-scroll-lock";

export function scrollLock(target) {
  disableBodyScroll(target)
}

export function enableScroll() {
  clearAllBodyScrollLocks()
}
