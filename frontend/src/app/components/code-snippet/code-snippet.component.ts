import { Component, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { bootstrapApplication } from '@angular/platform-browser';
import { IonContent } from '@ionic/angular';
import { HIGHLIGHT_OPTIONS, HighlightLoader, HighlightOptions } from 'ngx-highlightjs';
import { Subscription } from 'rxjs';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';
import { QuizService } from 'src/app/services/quiz.service';

@Component({
  selector: 'code-snippet',
  templateUrl: './code-snippet.component.html',
  styleUrls: ['./code-snippet.component.scss']
})

export class CodeSnippetComponent {
  @Input() questions: any
  @Input() duration: number = 2;
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  @Output() answerSubmitted: EventEmitter<any> = new EventEmitter<any>();
  @ViewChild(IonContent, { static: false }) content: IonContent | undefined;
  //czas
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;
  displayProgressBar: boolean = true;

  userAnswer: string = '';
  messages: { content: string, type: 'question' | 'user' | 'bot' }[] = [];
  private quizSubscription: Subscription | undefined;
  constructor(private quizService: QuizService) {
  }

  ngOnInit() {
    this.startTimer();
    this.addBotQuestion();
  }

  onScroll(event: any) {
    this.displayProgressBar = event.detail.scrollTop < 50;
  }

  endGame() {
    console.log('Game over!');
  }

  private addBotQuestion() {
    const botQuestion = this.questions.content;
    this.messages.push({ content: botQuestion, type: 'question' });
  }

  ngOnDestroy() {
    if (this.quizSubscription) {
      this.quizSubscription.unsubscribe();
    }
  }

  async nextQuestion(): Promise<void> {
    const userMessage = this.userAnswer.trim();
    if (userMessage) {
      this.answerSubmitted.emit(userMessage);
      this.messages.push({ content: userMessage, type: 'user' });

      // Unsubscribe before making a new subscription
      if (this.quizSubscription) {
        this.quizSubscription.unsubscribe();
      }

      this.quizSubscription = this.quizService.getBotComment().subscribe({
        error: (err) => console.log(err),
        next: (comment) => {
          this.messages.push({ content: comment, type: 'bot' });
          this.addBotQuestion();
          setTimeout(() => this.scrollToBottom(), 50);
          this.userAnswer = '';
        }
      });
    }
  }

  private scrollToBottom() {
    try {
      this.content!.scrollToBottom(300);
    } catch (err) {
      console.error(err);
    }
  }

  private startTimer() {
    let time = 60 * this.duration;
    this.displayTime = this.formatTime(time);
    this.timer = setInterval(() => {
      time--;
      this.progress = time / 300;
      this.displayTime = this.formatTime(time);
      if (time <= 0) {
        clearInterval(this.timer);
      }
    }, 1000);
  }

  private formatTime(time: number): string {
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;
    this.quizService.setTimeStatus(`${minutes}:${seconds < 10 ? '0' + seconds : seconds}`);
    return `Time left ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
  }

}

