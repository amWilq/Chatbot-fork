import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'app-quiz-summary',
  templateUrl: './quiz-summary.component.html',
  styleUrls: ['./quiz-summary.component.scss']
})
export class QuizSummaryComponent implements OnInit {
  @Input() quizResponse!: any ;

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
