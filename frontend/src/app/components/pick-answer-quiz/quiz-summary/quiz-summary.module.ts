import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';
import { QuizSummaryComponent } from './quiz-summary.component';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [QuizSummaryComponent],
  exports: [QuizSummaryComponent]
})
export class QuizSummaryComponentModule {}
