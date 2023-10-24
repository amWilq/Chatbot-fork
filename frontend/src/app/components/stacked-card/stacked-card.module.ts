import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { StackedCardComponent } from './stacked-card-component';

@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [StackedCardComponent],
  exports: [StackedCardComponent]
})
export class StackedCardComponentModule {}
