import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { QuizComponent } from './quiz-component';

import { QuizComponentPageRoutingModule } from './quiz-routing.module';
import { ExploreContainerComponentModule } from 'src/app/components/explore-container/explore-container.module';
import { PickAnswerQuizComponentModule } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.module';
import { QuizSummaryComponentModule } from 'src/app/components/pick-answer-quiz/quiz-summary/quiz-summary.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    ExploreContainerComponentModule,
    QuizComponentPageRoutingModule,
    PickAnswerQuizComponentModule,
    QuizSummaryComponentModule
  ],
  declarations: [QuizComponent]
})
export class QuizModule {}
