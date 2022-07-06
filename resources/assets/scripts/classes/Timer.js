import moment from 'moment';
import {emptyCart} from "../util/AjaxEvents";
export default class Timer {
   constructor() {
     this.element = document.getElementById('24h_timer')
     if( this.element ) {
       this.end = this.element.parentElement.getAttribute('data-end')
       this.start_timer(this.end)
     }

   }

  start_timer(end) {
    setInterval(() =>{
      const eventTime= end
      const currentTime = moment().unix()
      const duration = moment.duration( moment.unix(eventTime).diff(moment.unix(currentTime),'milliseconds') );
      if( duration.hours() < 1 && duration.minutes() < 1 && duration.seconds() < 1  )
        return
      if( duration.days() < 1 ) {
        this.element.innerHTML = `
       <strong>${duration.hours() }</strong>h : <strong>${duration.minutes()}</strong>m : <strong>${duration.seconds()}</strong>s
       `;
      } else {
        this.element.innerHTML = `
       <strong>${duration.days() }</strong>d : <strong>${duration.hours() }</strong>h : <strong>${duration.minutes()}</strong>m : <strong>${duration.seconds()}</strong>s
       `;
      }

    }, 1000);


  }

  static maybe_display_30_min_cart_to_checkout(duration) {
    let  timer = duration,  minutes, seconds;

    if( document.querySelector('.timer') ) {

      this.cart_interval = setInterval(() => {
        minutes = parseInt((timer / 60) % 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        if (timer === 0) {
          clearInterval(this.cart_interval)
          emptyCart()
        }

        document.querySelector('.timer').innerHTML = `
       Remaining time to purchase 00: <strong>${minutes}</strong>m : <strong>${seconds}</strong>s
       `;
        --timer;
      }, 1000)

    }
  }

  static clear_cart_interval() {
     clearInterval(this.cart_interval)
  }
}


