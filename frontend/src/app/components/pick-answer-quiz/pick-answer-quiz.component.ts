import { Component, EventEmitter, Input, Output } from '@angular/core';
import { AlertController } from '@ionic/angular';
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

  selectedAnswer: string | null = null;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
  question: QuizQuestion | null = null;
  loading: boolean = true;
  loadingNextQuestion: boolean = false;
  questionStartTime: number | null = null;

  constructor(
    private quizService: QuizService,
    private assessmentsService: AssessmentsService,
    private alertController: AlertController,
    private timerService: TimerService
  ) {
  }

  async ngOnInit() {
    this.timerService.startCountdown(this.duration / 600); // zamiana na minuty
    await this.getQuestion();
    this.timerService.getTime().subscribe(time => {
      const minutes = Math.floor(time / 60000);
      const seconds = Math.floor((time % 60000) / 1000);
      this.displayTime = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
      const initialTime = this.timerService.getInitialCountdownValue();
      this.progress = (time / initialTime);
      this.quizService.setTimeStatus(this.displayTime);
    });
  }

  async getQuestion(): Promise<void> {
    this.loading = true;
    this.timerService.pauseCountdown();
    this.assessmentsService.getGenerateOutput(this.assessmentTypeName, this.assessmentId).subscribe({
      next: (response) => {
        this.question = response.data.question;
        this.timerService.resumeCountdown();
        this.loading = false;
      },
      error: (error) => console.log(error)
    });
  }


  async setUserAnswer(): Promise<void> {
    this.loadingNextQuestion = true;
    this.timerService.pauseCountdown();
    const timeSpent = this.timerService.getTimeSpent();
    console.log('Time spent on question in milliseconds:', timeSpent);
    if (timeSpent) {
      await this.assessmentsService.sendUserAnswer(this.assessmentTypeName, this.assessmentId, this.selectedAnswer, timeSpent).subscribe({
        next: (response) => {
          this.loadingNextQuestion = false;
          console.log(response);
          if (response) {
            this.presentAlert(response);
          }
        }
      });
    }
  }

  onSelectedAnswer(answer: string) {
    this.selectedAnswer = answer;
  }

  async presentAlert(response: any) {
    const headerClass = response.data.isCorrect === true ? 'alert-head-correct ' : 'alert-head-incorrect';
    const alert = await this.alertController.create({
      header: response.data.isCorrect,
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
