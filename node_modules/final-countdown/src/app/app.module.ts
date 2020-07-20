import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';

import {AppComponent} from './app.component';
import {FinalCountdownModule} from './modules/final-countdown/final-countdown.module';


@NgModule({
	declarations: [
		AppComponent
	],
	imports: [
		BrowserModule,
		FormsModule,
		FinalCountdownModule
	],
	providers: [],
	bootstrap: [AppComponent]
})
export class AppModule {
}
