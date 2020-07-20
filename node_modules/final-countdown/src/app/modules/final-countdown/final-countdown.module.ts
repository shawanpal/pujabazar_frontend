import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {FinalCountdownComponent} from './final-countdown.component';

@NgModule({
	imports: [
		CommonModule
	],
	declarations: [FinalCountdownComponent],
	exports: [FinalCountdownComponent]
})
export class FinalCountdownModule {
}
