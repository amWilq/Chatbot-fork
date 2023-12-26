import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { CodeSnippetComponent } from './code-snippet.component';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [CodeSnippetComponent],
  exports: [CodeSnippetComponent],

})
export class CodeSnippetComponentModule {}

