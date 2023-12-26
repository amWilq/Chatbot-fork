import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HomePageComponent } from './home-page-component';

import { HomePageRoutingModule } from './home-page-routing.module';
import { StackedCardComponentModule } from 'src/app/components/stacked-card/stacked-card.module';
import { CustomMatCardComponentModule } from 'src/app/components/custom-mat-card/custom-mat-card.module';
import { PickAnswerQuizComponentModule } from 'src/app/components/pick-answer-quiz/pick-answer-quiz.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    StackedCardComponentModule,
    CustomMatCardComponentModule,
    HomePageRoutingModule,
    PickAnswerQuizComponentModule,
  ],
  declarations: [HomePageComponent]
})
export class HomePageModule {}
