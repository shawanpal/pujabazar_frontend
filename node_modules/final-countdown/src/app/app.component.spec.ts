import {FormsModule} from '@angular/forms';
import {async, TestBed} from '@angular/core/testing';
import {AppComponent} from './app.component';
import {FinalCountdownModule} from './modules/final-countdown/final-countdown.module';

describe('AppComponent', () => {
	beforeEach(async(() => {
		TestBed.configureTestingModule({
			declarations: [
				AppComponent
			],
			imports: [
				FormsModule,
				FinalCountdownModule
			]
		}).compileComponents();
	}));

	it('should create the app', async(() => {
		const fixture = TestBed.createComponent(AppComponent);
		const app = fixture.debugElement.componentInstance;
		expect(app).toBeTruthy();
	}));

	it(`should have default countdown`, async(() => {
		const fixture = TestBed.createComponent(AppComponent);
		const app: AppComponent = fixture.debugElement.componentInstance;
		expect(app.endDate).toBeUndefined();
		app.ngOnInit();
		expect(app.endDate).toBeDefined();

	}));
});
