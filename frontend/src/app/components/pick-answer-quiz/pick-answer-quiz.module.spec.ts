import { TestBed } from '@angular/core/testing';
import { PickAnswerQuizComponentModule } from './pick-answer-quiz.module';

describe('PickAnswerQuizComponentModule', () => {
  let pipe: PickAnswerQuizComponentModule;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PickAnswerQuizComponentModule]
    });
    pipe = TestBed.inject(PickAnswerQuizComponentModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
