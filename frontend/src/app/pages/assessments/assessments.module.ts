import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AssessmentsComponent, } from './assessments-component';
import { PickAnswerQuizComponentModule } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.module';
import { QuizSummaryComponentModule } from 'src/app/components/pick-answer-quiz/quiz-summary/quiz-summary.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    PickAnswerQuizComponentModule,
    QuizSummaryComponentModule
  ],
  declarations: [AssessmentsComponent],
  exports: [AssessmentsComponent]

})
export class AssessmentsModule {}
