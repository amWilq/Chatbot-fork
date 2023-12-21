import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { QuizModel } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'app-quiz-summary',
  templateUrl: './quiz-summary.component.html',
  styleUrls: ['./quiz-summary.component.scss']
})
export class QuizSummaryComponent implements OnInit {
  @Input() quizResponse: QuizModel[] = [] ;

  @Output() replayQuiz: EventEmitter<void> = new EventEmitter<void>();
  @Output() returnToMenu = new EventEmitter<void>();

  ngOnInit(): void {
  }

  onReplayQuiz(): void {
    this.replayQuiz.emit();
  }

  onReturnToMenu(): void {
    this.returnToMenu.emit();
  }
}
