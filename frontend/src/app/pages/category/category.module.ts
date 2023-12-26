import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CategoryComponent } from './category-component';

import { CategoryComponentRoutingModule } from './category-routing.module';
import { AssessmentsModule } from '../assessments/assessments.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    CategoryComponentRoutingModule,
    AssessmentsModule
  ],
  declarations: [CategoryComponent],
})
export class CategoryModule {}
