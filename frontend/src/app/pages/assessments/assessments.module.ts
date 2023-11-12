import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AssessmentsComponent, } from './assessments-component';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
  ],
  declarations: [AssessmentsComponent],
  exports: [AssessmentsComponent]

})
export class AssessmentsModule {}
