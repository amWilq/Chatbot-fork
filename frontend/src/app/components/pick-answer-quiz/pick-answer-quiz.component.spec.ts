import { ComponentFixture, TestBed } from '@angular/core/testing';
import { PickAnswerQuizComponent } from './pick-answer-quiz.component';
import { QuizService } from 'src/app/services/quiz.service';
import { TimerService } from 'src/app/services/time.service';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { AlertController, IonicModule } from '@ionic/angular';
import { AssessmentsService } from 'src/app/services/assessments.service';
import { of } from 'rxjs';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';

describe('PickAnswerQuizComponent', () => {
  let component: PickAnswerQuizComponent;
  let fixture: ComponentFixture<PickAnswerQuizComponent>;
  let quizService: QuizService;
  let timerService: TimerService;
  let alertController: AlertController;
  let assessmentsService: AssessmentsService;

  const mockQuestion = {
    // Define a mock QuizQuestion object here
  };

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [PickAnswerQuizComponent],
      imports: [HttpClientTestingModule, IonicModule],
      providers: [
        QuizService,
        TimerService,
        AlertController,
        AssessmentsService,
      ],
    });
    fixture = TestBed.createComponent(PickAnswerQuizComponent);
    component = fixture.componentInstance;
    quizService = TestBed.inject(QuizService);
    timerService = TestBed.inject(TimerService);
    alertController = TestBed.inject(AlertController);
    assessmentsService = TestBed.inject(AssessmentsService);
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should initialize component properties', () => {
    expect(component.selectedAnswer).toBeNull();
  });

  it('should start countdown timer on ngOnInit', () => {
    spyOn(timerService, 'startCountdown');
    component.duration = 5;
    component.ngOnInit();
    expect(timerService.startCountdown).toHaveBeenCalledWith(5 / 600);
  });

  it('should get a question and resume countdown', async () => {
    spyOn(assessmentsService, 'getGenerateOutput').and.returnValue(
      of({ data: { question: mockQuestion } })
    );
    spyOn(timerService, 'resumeCountdown');

    await component.getQuestion();

    expect(component.question).toEqual(mockQuestion as QuizQuestion);
    expect(timerService.resumeCountdown).toHaveBeenCalled();
    expect(component.loading).toBeFalsy();
  });

  it('should set user answer and send it to assessmentsService', async () => {
    component.selectedAnswer = 'A';
    spyOn(timerService, 'pauseCountdown');
    spyOn(timerService, 'getTimeSpent').and.returnValue(1000); // 1 second

    spyOn(assessmentsService, 'sendUserAnswer').and.returnValue(
      of({ data: { isCorrect: true, explanation: 'Correct answer' } })
    );

    await component.setUserAnswer();

    expect(timerService.pauseCountdown).toHaveBeenCalled();
    expect(assessmentsService.sendUserAnswer).toHaveBeenCalledWith(
      component.assessmentTypeName,
      component.assessmentId,
      'A',
      1000
    );
  });

  it('should present an alert with correct header class', async () => {
    spyOn(alertController, 'create').and.callFake(() => {
      return Promise.resolve({
        present: () => Promise.resolve() as Promise<void>,
      } as HTMLIonAlertElement);
    });

    const response = {
      data: { isCorrect: true, explanation: 'Correct answer' },
    };

    await component.presentAlert(response);

    expect(alertController.create).toHaveBeenCalledWith({
      header: 'true',
      cssClass: 'alert-head-correct',
      message: 'Correct answer',
      buttons: [
        {
          text: 'OK',
          handler: jasmine.any(Function),
        },
      ],
    });
  });

});
