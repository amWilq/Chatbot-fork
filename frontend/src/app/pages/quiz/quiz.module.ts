import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { QuizComponent } from './quiz-component.';

import { QuizComponentPageRoutingModule } from './quiz-routing.module';
import { ExploreContainerComponentModule } from 'src/app/components/explore-container/explore-container.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    ExploreContainerComponentModule,
    QuizComponentPageRoutingModule
  ],
  declarations: [QuizComponent]
})
export class QuizModule {}
