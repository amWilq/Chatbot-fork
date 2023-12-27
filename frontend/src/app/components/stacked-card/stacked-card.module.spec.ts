import { TestBed } from '@angular/core/testing';
import { StackedCardComponentModule } from './stacked-card.module';

describe('StackedCardComponentModule', () => {
  let pipe: StackedCardComponentModule;

  beforeEach(() => {
    TestBed.configureTestingModule({ providers: [StackedCardComponentModule] });
    pipe = TestBed.inject(StackedCardComponentModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
