import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CategoryComponent } from './category-component';

import { CategoryComponentRoutingModule } from './category-routing.module';
import { ExploreContainerComponentModule } from 'src/app/components/explore-container/explore-container.module';
import { AssessmentsModule } from '../assessments/assessments.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    ExploreContainerComponentModule,
    CategoryComponentRoutingModule,
    AssessmentsModule
  ],
  declarations: [CategoryComponent],
})
export class CategoryModule {}
