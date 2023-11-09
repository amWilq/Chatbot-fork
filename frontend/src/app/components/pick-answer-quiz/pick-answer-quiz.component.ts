import { Component, Input, Output, EventEmitter } from '@angular/core';
import { QuizQuestion } from 'src/app/entities/quiz-question.model';

@Component({
  selector: 'pick-answer-quiz',
  templateUrl: './pick-answer-quiz.component.html',
  styleUrls: ['./pick-answer-quiz.component.scss']
})
export class PickAnswerQuizComponent {
  @Input() questions: QuizQuestion[] = [];
  @Output() answerSelected = new EventEmitter<{ selected: string; correct: boolean }>();
  currentQuestionIndex: number = 0;
  selectedAnswer: string | null = null;

  selectAnswer(answer: string) {
    this.selectedAnswer = answer;
    const isCorrect = this.questions[this.currentQuestionIndex].correctAnswer === answer;
    this.answerSelected.emit({ selected: answer, correct: isCorrect });
  }

  nextQuestion() {
    if (this.currentQuestionIndex < this.questions.length - 1) {
      this.currentQuestionIndex++;
      this.selectedAnswer = null;
    } else {
      //show a results screen or reset the quiz
    }
  }

  get currentQuestion(): QuizQuestion {
    return this.questions[this.currentQuestionIndex];
  }
}
