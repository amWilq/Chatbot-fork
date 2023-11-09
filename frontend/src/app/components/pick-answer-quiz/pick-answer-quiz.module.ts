import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';
import { PickAnswerQuizComponent } from './pick-answer-quiz.component';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [PickAnswerQuizComponent],
  exports: [PickAnswerQuizComponent]
})
export class PickAnswerQuizComponentModule {}
