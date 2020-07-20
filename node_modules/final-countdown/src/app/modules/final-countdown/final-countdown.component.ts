import {Component, Input, OnInit} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/interval';
import 'rxjs/add/operator/map';

@Component({
	selector: 'final-countdown',
	templateUrl: './final-countdown.component.html',
	styleUrls: ['./final-countdown.component.css']
})
export class FinalCountdownComponent implements OnInit {

	@Input()
	public endDate: string;

	@Input()
	overdue = 'Overdue!';

	@Input()
	days = 'days';

	@Input()
	hours = 'h';

	@Input()
	minutes = 'm';

	@Input()
	seconds = 's';

	@Input()
	left = 'left.';

	public counter: Observable<string>;

	constructor() {
		this.counter = Observable.interval(1000)
			.map(() => this.getCountDown());
	}

	ngOnInit() {
	}

	public getCountDown(): string {
		if (!this.endDate) {
			return '';
		}

		const actualDate = new Date();
		const endDate = new Date(this.endDate);

		const secondsToGo = endDate.getTime() - actualDate.getTime();

		if (secondsToGo <= 0) {
			return this.overdue;
		}

		const days = Math.floor(secondsToGo / (1000 * 60 * 60 * 24));
		const hours = Math.floor((secondsToGo % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		const minutes = Math.floor((secondsToGo % (1000 * 60 * 60)) / (1000 * 60));
		const seconds = Math.floor((secondsToGo % (1000 * 60)) / 1000);

		if (days > 0) {
			return `${days}${this.days} ${hours}${this.hours} ${minutes}${this.minutes} ${seconds}${this.seconds} ${this.left}`;
		} else if (hours > 0) {
			return `${hours}${this.hours} ${minutes}${this.minutes} ${seconds}${this.seconds} ${this.left}`;
		} else if (minutes > 0) {
			return `${minutes}${this.minutes} ${seconds}${this.seconds} ${this.left}`;
		} else {
			return `${seconds}${this.seconds} ${this.left}`;
		}
	}

}
