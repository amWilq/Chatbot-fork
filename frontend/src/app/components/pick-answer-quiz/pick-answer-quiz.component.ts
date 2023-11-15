import { Component, EventEmitter, Input, Output } from '@angular/core';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'pick-answer-quiz',
  templateUrl: './pick-answer-quiz.component.html',
  styleUrls: ['./pick-answer-quiz.component.scss']
})
export class PickAnswerQuizComponent {
  @Input() questions: any
  @Output() quizCompleted: EventEmitter<void> = new EventEmitter<void>();
  selectedAnswer: string | null = null;
  progress: number = 0;
  displayTime: string | undefined;
  timer: any | null = null;

  constructor() {
  }

  ngOnInit() {
    this.startTimer();
  }

  startTimer() {
    let time = 300; // Start from 300 seconds (5 minutes)
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

  formatTime(time: number): string {
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;
    return `Pozostało ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
  }

  nextQuestion() {
    console.log('Next question');
    this.quizCompleted.emit();
  }

  get currentQuestion(): QuizQuestion {
    return this.questions;
  }
}
