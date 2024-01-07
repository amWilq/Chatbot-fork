import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { FreeTextComponent } from './free-text.component';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [FreeTextComponent],
  exports: [FreeTextComponent],

})
export class FreeTextComponentModule {}

