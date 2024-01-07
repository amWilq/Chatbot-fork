import { Component, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { IonContent } from '@ionic/angular';
import { Subscription } from 'rxjs';
import { FreeText } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
import { QuizService } from 'src/app/services/quiz.service';
import { TimerService } from 'src/app/services/time.service';

@Component({
  selector: 'free-text',
  templateUrl: './free-text.component.html',
  styleUrls: ['./free-text.component.scss']
})

export class FreeTextComponent {

  @Input() assessmentTypeName: any
  @Input() assessmentId: any
  quizOver: boolean = false;
  private subscriptions: Subscription[] = [];
  question: FreeText | undefined = undefined;
  botAnswer: any;
  loadingNextQuestion: boolean = false;
  loadingUserAnswer: boolean = false;

  @Input() duration: number = 2;
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  @Output() answerSubmitted: EventEmitter<any> = new EventEmitter<any>();
  @ViewChild(IonContent, { static: false }) content: IonContent | undefined;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
  displayProgressBar: boolean = true;
  loading: boolean = false;
  testData: any;
  userAnswer: string = '';
  messages: { content: string, isCorrect?: boolean, type: 'question' | 'user' | 'bot' }[] = [];
  private quizSubscription: Subscription | undefined;

  public alertButtons = [
    {
      text: 'Cancel',
      role: 'cancel'
    },
    {
      text: 'OK',
      role: 'confirm'
    },
  ];

  constructor(
    private quizService: QuizService,
    private assessmentsService: AssessmentsService,
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

  async getQuestion(): Promise<void> {
    if (this.quizOver) {
      this.quizService.setQuizCompleted();
      return;
    }
    this.loading = true;
    this.timerService.pauseCountdown();
    this.loadingNextQuestion = true;
    this.subscriptions.push(
      this.assessmentsService.getGenerateOutput(this.assessmentTypeName, this.assessmentId).subscribe({
        next: (response) => {
          this.question = response.data.modelResponse;
          this.messages.push({ content: this.question!.message, type: 'question' });
          this.timerService.resumeCountdown();
          this.loading = false;
          this.loadingNextQuestion = false;
          this.scrollToBottom();
        },
        error: (error) => console.log(error)
      }));

  }

  onScroll(event: any) {
    this.displayProgressBar = event.detail.scrollTop < 50;
  }

  ngOnDestroy() {
    if (this.quizSubscription) {
      this.quizSubscription.unsubscribe();
    }
  }

  async setUserAnswer(): Promise<void> {
    this.loadingUserAnswer = true;
    this.timerService.pauseCountdown();
    const timeSpent = this.timerService.getTimeSpent();
    const userMessage = this.userAnswer.trim();
    if (userMessage) {
      this.scrollToBottom();
      this.answerSubmitted.emit(userMessage);
      this.messages.push({ content: userMessage, type: 'user' });
      if (timeSpent) {
        this.userAnswer = '';
        this.subscriptions.push(
          await this.assessmentsService.sendFreeTextUserAnswer(this.assessmentTypeName, this.assessmentId, userMessage, timeSpent).subscribe({
            next: () => {
              this.loadingUserAnswer = false;
              this.scrollToBottom();
              this.getQuestion();
            }
          }));
      }
    }
  }

  private scrollToBottom() {
    try {
      this.content?.scrollToPoint(0, 10000, 30000);
    } catch (err) {
      console.error(err);
    }
  }

  setResult(ev: any) {
    if (ev.detail.role === 'confirm') {
      this.quizOver = true;
    }
  }

}

