import { Component, EventEmitter, Input, Output } from '@angular/core';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';
import { QuizService } from 'src/app/services/quiz.service';

@Component({
  selector: 'pick-answer-quiz',
  templateUrl: './pick-answer-quiz.component.html',
  styleUrls: ['./pick-answer-quiz.component.scss']
})
export class PickAnswerQuizComponent {
  @Input() questions: any
  @Input() duration: number = 5;
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  selectedAnswer: string | null = null;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;

  constructor(private quizService: QuizService) {
  }

  ngOnInit() {
    this.startTimer();
  }

  nextQuestion() {
    console.log('Next question');
    this.quizCompleted.emit();
  }

  onSelectedAnswer(answer: string) {
    this.selectedAnswer = answer;
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
    return `Pozostało ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
  }

  get currentQuestion(): QuizQuestion {
    return this.questions;
  }
}
