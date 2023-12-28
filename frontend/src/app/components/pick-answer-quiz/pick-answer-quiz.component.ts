import { Component, EventEmitter, Input, Output } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Subscription } from 'rxjs';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
import { QuizService } from 'src/app/services/quiz.service';
import { TimerService } from 'src/app/services/time.service';
@Component({
  selector: 'pick-answer-quiz',
  templateUrl: './pick-answer-quiz.component.html',
  styleUrls: ['./pick-answer-quiz.component.scss']
})
export class PickAnswerQuizComponent {
  @Input() duration: number = 5;
  @Input() assessmentTypeName: any
  @Input() assessmentId: any
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  private subscriptions: Subscription[] = [];
  selectedAnswer: string | null = null;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
  question: QuizQuestion | null = null;
  loading: boolean = true;
  loadingNextQuestion: boolean = false;
  questionStartTime: number | null = null;
  quizOver: boolean = false;

  constructor(
    private quizService: QuizService,
    private assessmentsService: AssessmentsService,
    private alertController: AlertController,
    private timerService: TimerService
  ) {
  }

  async ngOnInit() {
    this.assessmentsService.initWebSocket(this.assessmentTypeName, this.assessmentId);
    this.timerService.startCountdown(this.duration / 600); // zamiana na minuty
    await this.getQuestion();
    this.subscriptions.push(
      this.timerService.getTime().subscribe(time => {
        const minutes = Math.floor(time / 60000);
        const seconds = Math.floor((time % 60000) / 1000);
        this.displayTime = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        const initialTime = this.timerService.getInitialCountdownValue();
        this.progress = (time / initialTime);
        this.quizService.setTimeStatus(this.displayTime);
        if (time === 0) {
          this.quizOver = true;
        }
      }));
  }
  ngOnDestroy() {
    this.subscriptions.forEach(subscription => subscription.unsubscribe());
  }
  async getQuestion(): Promise<void> {
    if (this.quizOver) {
      this.quizService.setQuizCompleted();
      return;
    }
    this.loading = true;
    this.timerService.pauseCountdown();
    this.subscriptions.push(
      this.assessmentsService.getGenerateOutput(this.assessmentTypeName, this.assessmentId).subscribe({
        next: (response) => {
          this.question = response.data.question;
          this.timerService.resumeCountdown();
          this.loading = false;
        },
        error: (error) => console.log(error)
      }));

  }

  async completeQuiz(): Promise<void> {
    this.loading = true;
    this.subscriptions.push(
      this.assessmentsService.completeAssessment(this.assessmentTypeName, this.assessmentId).subscribe({
        next: (response) => {
          console.error('response', response);
          this.loading = false;
        },
        error: (error) => console.log(error)
      }));
  }

  async setEndQuiz() {
    this.quizOver = true;
    this.quizService.setQuizCompleted();
    this.subscriptions.push(
      await this.assessmentsService.completeAssessment(this.assessmentTypeName, this.assessmentId).subscribe({
        next: (response) => {
          console.error('response', response);
          this.loading = false;
          // this.showSummary = true;
        },
        error: (error) => console.log(error)
      }));
  }

  async setUserAnswer(): Promise<void> {
    this.loadingNextQuestion = true;
    this.timerService.pauseCountdown();
    const timeSpent = this.timerService.getTimeSpent();
    console.log('Time spent on question in milliseconds:', timeSpent);
    if (timeSpent) {
      this.subscriptions.push(
        await this.assessmentsService.sendUserAnswer(this.assessmentTypeName, this.assessmentId, this.selectedAnswer, timeSpent).subscribe({
          next: (response) => {
            this.loadingNextQuestion = false;
            console.log(response);
            if (response) {
              this.presentAlert(response);
            }
          }
        }));
    }
  }

  onSelectedAnswer(answer: string) {
    this.selectedAnswer = answer;
  }

  async presentAlert(response: any) {
    const headerClass = response.data.isCorrect === true ? 'alert-head-correct' : 'alert-head-incorrect';
    const alert = await this.alertController.create({
      header: String(response.data.isCorrect),
      cssClass: headerClass,
      message: `${response.data.explanation}`,
      buttons: [{
        text: 'OK',
        handler: async () => {
          console.log('OK clicked');
          this.selectedAnswer = null;
          await this.getQuestion();
        }
      }]
    });
    await alert.present();
  }
}
