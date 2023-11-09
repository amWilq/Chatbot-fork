import { Component, EventEmitter, Input, Output } from '@angular/core';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'app-quiz-summary',
  templateUrl: './quiz-summary.component.html',
  styleUrls: ['./quiz-summary.component.scss']
})
export class QuizSummaryComponent {
  @Input() questions: QuizQuestion[] = [];
  @Input() answers: string[] = [];
  @Output() replayQuiz = new EventEmitter<void>();
  @Output() returnToMenu = new EventEmitter<void>();

  calculateScore(): number {
    let score = 0;
    this.questions.forEach((question, index) => {
      if (question.correctAnswer === this.answers[index]) {
        score++;
      }
    });
    return score;
  }

  onReplayQuiz() {
    this.replayQuiz.emit();
  }

  onReturnToMenu() {
    this.returnToMenu.emit();
  }
}
