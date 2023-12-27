import { TestBed } from '@angular/core/testing';
import { QuizSummaryComponentModule } from './quiz-summary.module';

describe('QuizSummaryComponentModule', () => {
  let pipe: QuizSummaryComponentModule;

  beforeEach(() => {
    TestBed.configureTestingModule({ providers: [QuizSummaryComponentModule] });
    pipe = TestBed.inject(QuizSummaryComponentModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
