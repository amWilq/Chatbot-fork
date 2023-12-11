import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { CodeSnippetComponent } from './code-snippet.component';
import { HIGHLIGHT_OPTIONS, HighlightModule } from 'ngx-highlightjs';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule,HighlightModule],
  declarations: [CodeSnippetComponent],
  exports: [CodeSnippetComponent],
  providers: [
    {
      provide: HIGHLIGHT_OPTIONS,
      useValue: {
        fullLibraryLoader: () => import('highlight.js'),
      }
    }
  ],
})
export class CodeSnippetComponentModule {}
