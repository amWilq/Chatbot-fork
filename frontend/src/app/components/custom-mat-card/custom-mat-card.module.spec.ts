import { TestBed } from '@angular/core/testing';
import { CustomMatCardComponentModule } from './custom-mat-card.module';

describe('CustomMatCardComponentModule', () => {
  let pipe: CustomMatCardComponentModule;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CustomMatCardComponentModule]
    });
    pipe = TestBed.inject(CustomMatCardComponentModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
