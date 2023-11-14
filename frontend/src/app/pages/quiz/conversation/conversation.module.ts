import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ConversationComponent } from './conversation-component';
import { ConversationComponentPageRoutingModule } from './conversation-routing.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    ConversationComponentPageRoutingModule,
  ],
  declarations: [ConversationComponent]
})
export class ConversationModule {}
