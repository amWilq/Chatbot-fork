import { Component, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { AlertController, IonContent } from '@ionic/angular';
import { Subscription } from 'rxjs';
import { CodeSnippet, QuizQuestion } from 'src/app/entities/quiz-question.model';
import { AssessmentsService } from 'src/app/services/assessments.service';
import { QuizService } from 'src/app/services/quiz.service';
import { TimerService } from 'src/app/services/time.service';

@Component({
  selector: 'code-snippet',
  templateUrl: './code-snippet.component.html',
  styleUrls: ['./code-snippet.component.scss']
})

export class CodeSnippetComponent {

  @Input() assessmentTypeName: any
  @Input() assessmentId: any
  quizOver: boolean = false;
  private subscriptions: Subscription[] = [];
  question: CodeSnippet | undefined = undefined;
  botAnswer: any;

  @Input() duration: number = 2;
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  @Output() answerSubmitted: EventEmitter<any> = new EventEmitter<any>();
  @ViewChild(IonContent, { static: false }) content: IonContent | undefined;
  //czas
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
  displayProgressBar: boolean = true;
  loading: boolean = false;
  testData: any;
  userAnswer: string = '';
  messages: { content: string, type: 'question' | 'user' | 'bot' }[] = [];
  private quizSubscription: Subscription | undefined;

  public alertButtons = [
    {
      text: 'Cancel',
      role: 'cancel',
    },
    {
      text: 'OK',
      role: 'confirm',

    },
  ];

  constructor(
    private quizService: QuizService,
    private assessmentsService: AssessmentsService,
    private alertController: AlertController,
    private timerService: TimerService
  ) {
  }

  async ngOnInit() {
    console.log(this.assessmentTypeName, this.assessmentId)
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
    // this.addBotQuestion();
  }

  async getQuestion(): Promise<void> {

    if (this.quizOver) {
      this.quizService.setQuizCompleted();
      return;
    }
    this.loading = true;
    this.timerService.pauseCountdown();
    console.log(this.assessmentTypeName, this.assessmentId)
    this.subscriptions.push(
      this.assessmentsService.getGenerateOutput(this.assessmentTypeName, this.assessmentId).subscribe({
        next: (response) => {
          this.question = response.data.snippet;
          console.log(this.question)
          this.timerService.resumeCountdown();
          this.loading = false;
        },
        error: (error) => console.log(error)
      }));

  }

  onScroll(event: any) {
    this.displayProgressBar = event.detail.scrollTop < 50;
  }

  // setResult(ev: any) {
  //   console.log(`Dismissed with role: ${ev.detail.role}`);
  //   this.quizCompleted.emit();
  //   this.quizService.setQuizsStatus(true);
  // }

  // private addBotQuestion() {
  //   this.loading = true;
  //   const botQuestion = this.questions.content;
  //   this.messages.push({ content: botQuestion, type: 'question' });
  // }

  ngOnDestroy() {
    if (this.quizSubscription) {
      this.quizSubscription.unsubscribe();
    }
  }

  async setUserAnswer(): Promise<void> {
    // this.loadingNextQuestion = true;
    this.timerService.pauseCountdown();
    const timeSpent = this.timerService.getTimeSpent();
    console.log('Time spent on question in milliseconds:', timeSpent);
    const userMessage = this.userAnswer.trim();
    console.log(userMessage)
    if (userMessage) {
      this.answerSubmitted.emit(userMessage);
      this.messages.push({ content: userMessage, type: 'user' });
      if (timeSpent) {
        this.subscriptions.push(
          await this.assessmentsService.sendCodeSnippetUserAnswer(this.assessmentTypeName, this.assessmentId, userMessage, timeSpent).subscribe({
            next: (response) => {
              // this.loadingNextQuestion = false;
              this.botAnswer = response.data;
              console.log(response);
              // if (response) {
              //   this.presentAlert(response);
              // }
            }
          }));
      }
    }
  }


  // async nextQuestion(): Promise<void> {
  //   this.loading = true;
  //   const userMessage = this.userAnswer.trim();
  //   if (userMessage) {
  //     this.answerSubmitted.emit(userMessage);
  //     this.messages.push({ content: userMessage, type: 'user' });

  //     // Unsubscribe before making a new subscription
  //     if (this.quizSubscription) {
  //       this.quizSubscription.unsubscribe();
  //     }

  //     this.quizSubscription = this.quizService.getBotComment().subscribe({
  //       error: (err) => console.log(err),
  //       next: (comment) => {
  //         this.messages.push({ content: comment, type: 'bot' });
  //         this.addBotQuestion();
  //         setTimeout(() => this.scrollToBottom(), 50);
  //         this.userAnswer = '';

  //         // Opóźnienie ustawienia loading na false o 2 sekundy
  //         setTimeout(() => {
  //           this.loading = false;
  //         }, 2000);
  //       }
  //     });
  //   }
  // }

  private scrollToBottom() {
    try {
      this.content!.scrollToBottom(300);
    } catch (err) {
      console.error(err);
    }
  }


}

