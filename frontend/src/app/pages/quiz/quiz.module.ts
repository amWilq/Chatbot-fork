import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { QuizComponent } from './quiz-component';

import { QuizComponentPageRoutingModule } from './quiz-routing.module';
import { PickAnswerQuizComponentModule } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.module';
import { QuizSummaryComponentModule } from 'src/app/components/quiz-summary/quiz-summary.module';
import { CodeSnippetComponentModule } from 'src/app/components/code-snippet/code-snippet.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    QuizComponentPageRoutingModule,
    PickAnswerQuizComponentModule,
    QuizSummaryComponentModule,
    CodeSnippetComponentModule,
  ],
  declarations: [QuizComponent]
})
export class QuizModule {}
